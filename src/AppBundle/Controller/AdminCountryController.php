<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Country;
use AppBundle\Form\CountryType;


/**
* @Route("/admin/countries", name="admin_country_")
*/
class AdminCountryController extends Controller
{
	/**
    * @Route("/{country_id}", requirements={"country_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$country_id=null){
        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Country::class);

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($country_id){
            $request->query->set('id',intval($country_id));
        }
        $params = $request->query->all();
        $countries = $rep->search($params,$limit,$offset);

    	$item = new Country();
    	$form = $this->createForm(CountryType::class,$item);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$em->persist($item);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_country_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($countries)){
                    throw $this->createNotFoundException("Departement introuvable");
                }
                $countries = $countries[0];
            }

            $json = $this->get("serializer")->serialize($countries,'json',array('groups' => array('group1')));
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/country/index.html.twig',array(
    		"countries"=>$countries,
    		"form"=>$form->createView()
    	));
    }

    /**
    * @Route("/update/{country_id}", requirements={"country_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$country_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Country::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($country_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CountryType::class,$item,array('csrf_protection' => false));
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
    * @Route("/delete/{country_id}", requirements={"country_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$country_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Country::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($country_id))){
            throw $this->createNotFoundException();
        }

        $em->remove($item);
        $em->flush();
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);

        return $this->json($result);
    }
}
