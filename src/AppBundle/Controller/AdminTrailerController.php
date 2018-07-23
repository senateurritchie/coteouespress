<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\MovieTrailer;
use AppBundle\Form\MovieTrailerType;

/**
* @Route("/admin/trailers", name="admin_trailer_")
*/
class AdminTrailerController extends Controller
{
	/**
    * @Route("/{trailer_id}", requirements={"trailer_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$trailer_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(MovieTrailer::class);

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($trailer_id){
            $request->query->set('id',intval($trailer_id));
        }
        $params = $request->query->all();
        $data = $rep->search($params,$limit,$offset);

    	$item = new MovieTrailer();
    	$item->setCreateAt(new \Datetime());
    	$form = $this->createForm(MovieTrailerType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$em->persist($item);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_trailer_index");
    	}

        if($request->isXmlHttpRequest()){
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
            $view;

            if(is_array($data)){
                $view = $this->render('admin/trailer/item-render.html.twig',array(
                    "data"=>$data,
                ));

            }
            else{
               
                $form2 = $this->createForm(MovieTrailerType::class,$data,[
                    'upload_dir' => $this->getParameter('public_upload_directory'),
                ]);

                $em->refresh($data);
                $view = $this->render('admin/trailer/selected-view.html.twig',array(
                    "data"=>$data,
                    "form"=>$form2->createView(),
                ));
            }
            
           

            $result['view'] = $view->getContent();

            $json = json_encode($result);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/trailer/index.html.twig',array(
    		"data"=>$data,
    		"form"=>$form->createView(),
    	));
    }

    /**
    * @Route("/update/{trailer_id}", requirements={"trailer_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$trailer_id){
        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(MovieTrailer::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($trailer_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(MovieTrailerType::class,$item,array('csrf_protection' => false));
        $form->submit($request->request->all());

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){

            $em->merge($item);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);
        }
        
        return $this->json($result);
    }

    /**
    * @Route("/delete/{trailer_id}", requirements={"trailer_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$trailer_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(MovieTrailer::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($trailer_id))){
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
    * @Route("/{trailer_id}/image/upload", requirements={"trailer_id":"\d+"}, name="upload_cover")
    * @Method("POST")
    */
    public function imageUploadAction(Request $request,$trailer_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(MovieTrailer::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];


        if(!($item = $rep->find($trailer_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(MovieTrailerType::class,$item,array(
            'csrf_protection' => false,
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ));
        $form->submit($request->files->all());

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $em->merge($item);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);
        }

        return $this->json($result);
    }
}
