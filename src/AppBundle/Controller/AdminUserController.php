<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;
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
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }
        
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
            $user->setCreateAt(new \Datetime());

            $roles = [];

            $userrole = new UserRole();
            $userrole->setUser($user);
            $userrole->setRole($form->get('roles')->getData());
            $userrole->setCreateAt(new \Datetime());
            $roles[] = $userrole;

            $privileges = $form->get('privileges')->getData();

            if(count($privileges)){
                foreach ($privileges as $key => $el) {
                    $userrole = new UserRole();
                    $userrole->setUser($user);
                    $userrole->setRole($el);
                    $userrole->setCreateAt(new \Datetime());
                    $roles[] = $userrole;
                }
            }

    		$em->persist($user);

            foreach ($roles as $el) {
                $em->persist($el);
            }


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
        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");


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
                $userrole = new UserRole();
                $userrole->setUser($user);
                $userrole->setRole($role);
                $userrole->setCreateAt(new \Datetime());
                $em->persist($userrole);
                $em->flush();
                $result['status'] = true;
                $result['message'] = "modification effectuée avec succès";
            }
        }

        return $this->json($result);
    }

    /**
    * @Route("/revoke-role/{user_id}", requirements={"user_id":"\d+"}, name="revoke_role")
    * @Method("POST")
    */
    public function revokeAction(Request $request,$user_id){
        // protection par role
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");

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
                $rep = $em->getRepository(UserRole::class);

                if(($userrole = $rep->findOneBy(["user"=>$user,"role"=>$role]))) {
                    $em->remove($userrole);
                    $em->flush();
                    $result['status'] = true;
                    $result['message'] = "modification effectuée avec succès";
                }
            }
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
