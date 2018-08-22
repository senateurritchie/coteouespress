<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\AcceptHeader;

use AppBundle\Entity\Country;
use AppBundle\Entity\Director;
use AppBundle\Entity\DirectorCountry;
use AppBundle\Form\DirectorType;
use AppBundle\Form\DirectorUploadImageType;


/**
* @Route("/admin/directors", name="admin_director_")
*/
class AdminDirectorController extends Controller
{
	/**
    * @Route("/{director_id}", requirements={"director_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$director_id=null){

        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Director::class);

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($director_id){
            $request->query->set('id',intval($director_id));
        }
        $params = $request->query->all();
        $data = $rep->search($params,$limit,$offset);

    	$item = new Director();
    	$item->setCreateAt(new \Datetime());
    	$form = $this->createForm(DirectorType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

    	$form->handleRequest($request);

        foreach ($form->all() as $child) {
            if (!$child->isValid() && count($child->getErrors())) {
                $error = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                $this->addFlash('notice-error',$error);
            }
        }



    	if($form->isSubmitted() && $form->isValid()){

    		$em->persist($item);

    		$countries = $form->get('pays')->getData();
            $db = [];
            foreach ($countries as $key => $country) {
                if(in_array($country->getId(), $db)) continue;
                
                $dc = new DirectorCountry();
                $dc->setDirector($item);
                $dc->setCountry($country);
                $dc->setCreateAt(new \Datetime());
                $em->persist($dc);
                $db[] = $country->getId();
            }


    		$em->flush();
            $this->addFlash('notice-success',"Votre élement a été ajouté avec succes");
    		return $this->redirectToRoute("admin_director_index",['cool'=>555]);
    	}

        // requete ajax
        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            
            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("Element introuvable");
                }
                $data = $data[0];
            }


            $result = array();
            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);
            $result['model'] = $json;
            $result['view'] = "";
            $view = null;

            if(is_array($data)){
                $view = $this->render('admin/director/item-render.html.twig',array(
                    "data"=>$data,
                ));
            }
            else{

                $form2 = $this->createForm(DirectorType::class,$data,[
                    "use_for"=>"update",
                    'upload_dir' => $this->getParameter('public_upload_directory'),
                    "action"=>$this->generateUrl("admin_director_update",["director_id"=>$data->getId()])
                ]);

                //$em->refresh($data);
                
                $formView = $form2->createView();

                $view = $this->render('admin/director/selected-view.html.twig',[
                    "data"=>$data,
                    "use_modal"=>"update",
                    "form"=>$formView,
                ]);
            }

            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                return $view;
            }
            
            if($view){
                $result['view'] = $view->getContent();
            }

            $json = json_encode($result);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/director/index.html.twig',array(
    		"data"=>$data,
    		"form"=>$form->createView(),
    	));
    }

    /**
    * @Route("/update/{director_id}", requirements={"director_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$director_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        $oldImage = $item->getImage();

        $form = $this->createForm(DirectorType::class,$item,array(
            'use_for' => "update",
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ));

        $form->handleRequest($request);

        foreach ($form->all() as $child) {
            if (!$child->isValid() && count($child->getErrors())) {
                $error = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                $this->addFlash('notice-error',$error);
            }
        }

        if($form->isSubmitted() && $form->isValid()){
            $em->merge($item);

            if(!$item->getImage() && $oldImage){
                $item->setImage($oldImage);
            }

            $countries = $form->get('pays')->getData();
            $db = [];
            foreach ($countries as $key => $country) {
                if(in_array($country->getId(), $db)) continue;
                
                $dc = new DirectorCountry();
                $dc->setDirector($item);
                $dc->setCountry($country);
                $dc->setCreateAt(new \Datetime());
                $em->persist($dc);
                $db[] = $country->getId();
            }

            if($oldImage && $item->getImage() && $item->getImage() != $oldImage){
                $path = $this->getParameter('public_upload_directory').'/'.basename($oldImage);
                unlink($path);
            }

            $em->flush();
            $this->addFlash('notice-success',"Votre élement a été modifié avec succes");
        }
        
        return $this->redirectToRoute('admin_director_index');
    }

    /**
    * @Route("/delete/{director_id}", requirements={"director_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        $em->remove($item);
        $em->flush();
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);

        return $this->json($result);
    }


    /**
    * @Route("/{director_id}/country/delete", requirements={"director_id":"\d+"}, name="delete_country")
    * @Method("POST")
    */
    public function countryDeleteAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException("Réalisateur introuvable");
        }


        $rep = $em->getRepository(DirectorCountry::class);
        $id = intval($request->request->get("country_id"));

        if(!($target = $rep->findOneBy(["director"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Pays introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $em->flush();
    
        return $this->json($result);
    }
}
