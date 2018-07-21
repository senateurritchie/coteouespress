<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Country;
use AppBundle\Entity\Director;
use AppBundle\Entity\DirectorCountry;
use AppBundle\Form\DirectorType;


/**
* @Route("/admin/directors", name="admin_director_")
*/
class AdminDirectorController extends Controller
{
	/**
    * @Route("/{director_id}", requirements={"director_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$director_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Director::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($director_id){
            $request->query->set('id',intval($director_id));
        }
        $params = $request->query->all();
        $data = $rep->search($params,$limit,$offset);

    	$item = new Director();
    	$item->setCreateAt(new \Datetime());
    	$form = $this->createForm(DirectorType::class,$item);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){

    		$item->setDescription(strip_tags(trim($item->getDescription())));
        	$item->setName(strip_tags(trim($item->getName())));

    		$em->persist($item);

    		$countries = $form->get('pays')->getData();

    		foreach ($countries as $key => $country) {
    			$dc = new DirectorCountry();
    			$dc->setDirector($item);
    			$dc->setCountry($country);
    			$dc->setCreateAt(new \Datetime());
    			$em->persist($dc);
    		}

    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_director_index");
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

    	return $this->render('admin/Director/index.html.twig',array(
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DirectorType::class,$item,array('csrf_protection' => false));
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
    * @Route("/delete/{director_id}", requirements={"director_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this page!');

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
    * @Route("/{director_id}/country/insert", requirements={"director_id":"\d+"}, name="insert_country")
    * @Method("POST")
    */
    public function countryInsertAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];

        $country_id = $request->request->get("country_id");

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        if(!($country = $rep_c->findOneBySlug($country_id))){
            throw $this->createNotFoundException();
        }

        $rep = $em->getRepository(DirectorCountry::class);
        if(!($dc = $rep->findOneBy(["director"=>$item,"country"=>$country]))){
        	$dc = new DirectorCountry();
	        $dc->setDirector($item);
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
    * @Route("/{director_id}/country/delete", requirements={"director_id":"\d+"}, name="insert_country")
    * @Method("POST")
    */
    public function countryDeleteAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];

        $country_id = $request->request->get("country_id");

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        if(!($country = $rep_c->findOneBySlug($country_id))){
            throw $this->createNotFoundException();
        }

        $rep = $em->getRepository(DirectorCountry::class);
        if(($dc = $rep->findOneBy(["director"=>$item,"country"=>$country]))){
            $em->remove($dc);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($dc,'json',array("groups"=>["group1"])),true);
        }

        return $this->json($result);
    }

    /**
    * @Route("/{director_id}/image/upload", requirements={"director_id":"\d+"}, name="insert_country")
    * @Method("POST")
    */
    public function imageUploadAction(Request $request,$director_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Director::class);
        $rep_c = $em->getRepository(Country::class);
        $result = ["status"=>false];

        $country_id = $request->request->get("country_id");

        if(!($item = $rep->find($director_id))){
            throw $this->createNotFoundException();
        }

        if(!($country = $rep_c->findOneBySlug($country_id))){
            throw $this->createNotFoundException();
        }

        $rep = $em->getRepository(DirectorCountry::class);
        if(($dc = $rep->findOneBy(["director"=>$item,"country"=>$country]))){
            

            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($dc,'json',array("groups"=>["group1"])),true);
        }

        return $this->json($result);
    }
}
