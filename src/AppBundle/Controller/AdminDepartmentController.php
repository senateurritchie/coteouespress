<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Department;
use AppBundle\Form\DepartmentType;

/**
* @Route("/admin/department", name="admin_department_")
*/
class AdminDepartmentController extends Controller
{
	/**
    * @Route("/{department_id}", requirements={"department_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$department_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Department::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($department_id){
            $request->query->set('id',intval($department_id));
        }
        $params = $request->query->all();
        $departments = $rep->search($params,$limit,$offset);

    	$department = new Department();
    	$form = $this->createForm(DepartmentType::class,$department);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
            $department->setCreateAt(new \Datetime());
    		$em->persist($department);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_department_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($departments)){
                    throw $this->createNotFoundException("Departement introuvable");
                }
                $departments = $departments[0];
            }

            $json = $this->get("serializer")->serialize($departments,'json',array('groups' => array('group1')));
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/department/index.html.twig',array(
    		"departments"=>$departments,
    		"form"=>$form->createView()
    	));
    }

    /**
    * @Route("/update/{department_id}", requirements={"department_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$department_id){
        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Department::class);
        $result = ["status"=>false];

        if(!($role = $rep->find($department_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DepartmentType::class,$role,array('csrf_protection' => false));
        $form->submit($request->request->all());

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $em->merge($role);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($role,'json',array("groups"=>["group1"])),true);
        }
        

        return $this->json($result);
    }

    /**
    * @Route("/delete/{department_id}", requirements={"department_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$department_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Department::class);
        $result = ["status"=>false];

        if(!($department = $rep->find($department_id))){
            throw $this->createNotFoundException();
        }

        $em->remove($department);
        $em->flush();
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $result["data"] = json_decode($this->get("serializer")->serialize($department,'json',array("groups"=>["group1"])),true);

        return $this->json($result);
    }
}
