<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Country;
use AppBundle\Entity\Actor;
use AppBundle\Entity\ActorCountry;
use AppBundle\Form\ActorType;
use AppBundle\Form\ActorUploadImageType;



/**
* @Route("/admin/actors", name="admin_actor_")
*/
class AdminActorController extends Controller
{
	/**
    * @Route("/{actor_id}", requirements={"actor_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$actor_id=null){
        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Actor::class);

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($actor_id){
            $request->query->set('id',intval($actor_id));
        }
        $params = $request->query->all();
        $data = $rep->search($params,$limit,$offset);

    	$item = new Actor();
    	$item->setCreateAt(new \Datetime());
    	$form = $this->createForm(ActorType::class,$item);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){

    		$item->setDescription(strip_tags(trim($item->getDescription())));
        	$item->setName(strip_tags(trim($item->getName())));

    		$em->persist($item);

    		$countries = $form->get('pays')->getData();

    		foreach ($countries as $key => $country) {
    			$dc = new ActorCountry();
    			$dc->setActor($item);
    			$dc->setCountry($country);
    			$dc->setCreateAt(new \Datetime());
    			$em->persist($dc);
    		}

    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_Actor_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("Departement introuvable");
                }
                $data = $data[0];
            }

            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);

            if(!is_array($data)){
            	$countries = $data->getCountries();

            	foreach ($countries as $key => $el) {
            		$json['countries'][] = json_decode($this->get("serializer")->serialize($el->getCountry(),'json',array('groups' => array('group1'),"attributes"=>["id","name","slug"])),true);
            	}
            }
        

            $json = json_encode($json);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/Actor/index.html.twig',array(
    		"data"=>$data,
    		"form"=>$form->createView(),
    	));
    }

    /**
    * @Route("/update/{actor_id}", requirements={"actor_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$actor_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Actor::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($actor_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ActorType::class,$item,array('csrf_protection' => false));
        $form->submit($request->request->all());

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
        	$item->setDescription(strip_tags(trim($item->getDescription())));
        	$item->setName(strip_tags(trim($item->getName())));

            $em->merge($item);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);
        }
        
        return $this->json($result);
    }

    /**
    * @Route("/delete/{actor_id}", requirements={"actor_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$actor_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Actor::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($actor_id))){
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
    * @Route("/{actor_id}/country/insert", requirements={"actor_id":"\d+"}, name="insert_country")
    * @Method("POST")
    */
    public function countryInsertAction(Request $request,$actor_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Actor::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];

        $country_id = $request->request->get("country_id");

        if(!($item = $rep->find($actor_id))){
            throw $this->createNotFoundException();
        }

        if(!($country = $rep_c->findOneBySlug($country_id))){
            throw $this->createNotFoundException();
        }

        $rep = $em->getRepository(ActorCountry::class);
        if(!($dc = $rep->findOneBy(["actor"=>$item,"country"=>$country]))){
        	$dc = new ActorCountry();
	        $dc->setActor($item);
	        $dc->setCountry($country);
	        $dc->setCreateAt(new \Datetime());
	        $em->persist($dc);
	        $em->flush();
	        $result['status'] = true;
	        $result['message'] = "modification effectuée avec succès";
	        $result["data"] = json_decode($this->get("serializer")->serialize($dc,'json',array("groups"=>["group1"])),true);
        }

        return $this->json($result);
    }


    /**
    * @Route("/{actor_id}/country/delete", requirements={"actor_id":"\d+"}, name="delete_country")
    * @Method("POST")
    */
    public function countryDeleteAction(Request $request,$actor_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Actor::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];

        $country_id = $request->request->get("country_id");

        if(!($item = $rep->find($actor_id))){
            throw $this->createNotFoundException();
        }

        if(!($country = $rep_c->findOneBySlug($country_id))){
            throw $this->createNotFoundException();
        }

        $rep = $em->getRepository(ActorCountry::class);
        if(($dc = $rep->findOneBy(["actor"=>$item,"country"=>$country]))){
            $em->remove($dc);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($dc,'json',array("groups"=>["group1"])),true);
        }

        return $this->json($result);
    }

    /**
    * @Route("/{actor_id}/image/upload", requirements={"actor_id":"\d+"}, name="upload_cover")
    * @Method("POST")
    */
    public function imageUploadAction(Request $request,$actor_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Actor::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];


        if(!($item = $rep->find($actor_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ActorUploadImageType::class,$item,array(
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
