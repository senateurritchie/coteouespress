<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;


/**
* @Route("/admin/categories", name="admin_category_")
*/
class AdminCategoryController extends Controller
{
	/**
    * @Route("/{category_id}", requirements={"category_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$category_id=null){
        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Category::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($category_id){
            $request->query->set('id',intval($category_id));
        }
        $params = $request->query->all();
        $categories = $rep->search($params,$limit,$offset);

    	$item = new Category();
    	$form = $this->createForm(CategoryType::class,$item);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$em->persist($item);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_category_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($categories)){
                    throw $this->createNotFoundException("Departement introuvable");
                }
                $categories = $categories[0];
            }

            $json = $this->get("serializer")->serialize($categories,'json',array('groups' => array('group1')));
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/category/index.html.twig',array(
    		"categories"=>$categories,
    		"form"=>$form->createView()
    	));
    }

    /**
    * @Route("/update/{category_id}", requirements={"category_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$category_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Category::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($category_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CategoryType::class,$item,array('csrf_protection' => false));
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
    * @Route("/delete/{category_id}", requirements={"category_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$category_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Category::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($category_id))){
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
