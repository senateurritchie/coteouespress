<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Role;
use AppBundle\Form\RoleType;
/**
* @Route("/admin/roles", name="admin_role_")
*/
class AdminRoleController extends Controller
{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Role::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;
    	$params = $request->query->all();
    	$roles = $rep->findAll();

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

    	return $this->render('admin/role/index.html.twig',array(
    		"roles"=>$roles,
    		"form"=>$form->createView()
    	));
    }
}
