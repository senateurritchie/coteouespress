<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Role;
use AppBundle\Form\RoleType;
/**
* @Route("/admin/roles", name="admin_role_")
*/
class AdminRoleController extends Controller
{
	/**
    * @Route("/{role_id}", requirements={"role_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$role_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Role::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($role_id){
            $request->query->set('id',intval($role_id));
        }
        $params = $request->query->all();
        $roles = $rep->search($params,$limit,$offset);

    	$role = new Role();
    	$form = $this->createForm(RoleType::class,$role);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
            $role->setCreateAt(new \Datetime());
    		$em->persist($role);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_role_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($roles)){
                    throw $this->createNotFoundException("Role introuvable");
                }
                $roles = $roles[0];
            }

            $json = $this->get("serializer")->serialize($roles,'json');
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/role/index.html.twig',array(
    		"roles"=>$roles,
    		"form"=>$form->createView()
    	));
    }


    /**
    * @Route("/update/{role_id}", requirements={"role_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$role_id){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Role::class);
        $result = ["status"=>false];

        if(!($role = $rep->find($role_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(RoleType::class,$role,array('csrf_protection' => false));
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
            $result["data"] = json_decode($this->get("serializer")->serialize($role,'json'),true);
        }
        

        return $this->json($result);
    }

    /**
    * @Route("/delete/{role_id}", requirements={"role_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$role_id){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);
        $rep_role = $em->getRepository(Role::class);
        $result = ["status"=>false];


        if(!($role = $rep->find($role_id))){
            throw $this->createNotFoundException();
        }

        return $this->json($result);
    }
}
