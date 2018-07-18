<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Form\UserAdminRegistrationType;

/**
* @Route("/admin/users", name="admin_user_")
*/
class AdminUserController extends Controller
{
	/**
    * @Route("/{user_id}", requirements={"user_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$user_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(User::class);
        $rep_role = $em->getRepository(Role::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($user_id){
            $request->query->set('id',intval($user_id));
        }

    	$params = $request->query->all();

    	$users = $rep->search($params,$limit,$offset);

    	$user = new User();
    	$form = $this->createForm(UserAdminRegistrationType::class,$user);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){

            $roles = array($form->get('roles')->getData()->getLabel());
            $privileges = $form->get('privileges')->getData();

            if(count($privileges)){
                foreach ($privileges as $key => $el) {
                    $roles[] = $el->getLabel();
                }
            }

            $user->setRoles($roles);
    		$em->persist($user);
    		$em->flush();
    		$this->addFlash('notice-success',1);

    		return $this->redirectToRoute("admin_user_index");
    	}

        if($request->isXmlHttpRequest()){

            if(intval(@$params['id'])){
                if(empty($users)){
                    throw $this->createNotFoundException("Utilisateur introuvable");
                }

                $users = $users[0];

                if(count($users->getRoles())){
                    $role = array_slice($users->getRoles(), 0,1);
                    if(($role = $rep_role->findOneBy(["label"=>$role]))){
                        $users->setMasterRole($role);
                    }

                    if(count($users->getRoles()) > 1){
                        $privileges = array_slice($users->getRoles(), 1);
                            if(($privileges = $rep_role->findBy(["label"=>$privileges]))){
                            $users->setPrivileges($privileges);
                        }
                    }
                }
            }

            $json = $this->get("serializer")->serialize($users,'json', array('groups' => array('group1')));

            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/user/index.html.twig',array(
            "users"=>$users,
            "roles"=>$rep_role->findAll(),
    		"form"=>$form->createView()
    	));
    }


    /**
    * @Route("/grant-role/{user_id}", requirements={"user_id":"\d+"}, name="grant_role")
    * @Method("POST")
    */
    public function grantAction(Request $request,$user_id){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);
        $rep_role = $em->getRepository(Role::class);
        $result = ["status"=>false];
        $role_id = intval($request->request->get("role_id"));


        if(!($user = $rep->find($user_id))){
            throw $this->createNotFoundException();
        }

        if(!($role = $rep_role->find($role_id))){
            throw $this->createNotFoundException();
        }

        $roles = $user->getRoles();
        if(!in_array($role->getLabel(), $roles)){
            if($role->getType() != "role"){
                $roles[] = $role->getLabel();
            }
            $user->setRoles($roles);
            $em->flush();
            $result['status'] = true;
        }

        return $this->json($result);
    }

    /**
    * @Route("/revoke-role/{user_id}", requirements={"user_id":"\d+"}, name="revoke_role")
    * @Method("POST")
    */
    public function revokeAction(Request $request,$user_id){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);
        $rep_role = $em->getRepository(Role::class);
        $result = ["status"=>false];
        $role_id = intval($request->request->get("role_id"));


        if(!($user = $rep->find($user_id))){
            throw $this->createNotFoundException();
        }


        if(!($role = $rep_role->find($role_id))){
            throw $this->createNotFoundException();
        }

        $roles = $user->getRoles();
        if(in_array($role->getLabel(), $roles)){
            if($role->getType() != "role"){
                $pos = array_search($role->getLabel(),  $roles);
                unset($roles[$pos]);
            }

            $user->setRoles($roles);
            $em->flush();
            $result['status'] = true;
        }

        return $this->json($result);
    }

    /**
    * @Route("/email-registration", name="email_registration")
    */
    public function emailVisitorAction(Request $request){
    	return $this->render('admin/user/email/registration.html.twig',[
			"username"=>"Zacharie A. Assagou",
			"email"=>"zakeszako@yahoo.fr",
			"password"=>uniqid(),
            "token"=>User::generateToken(64)
    	]);
    }




}
