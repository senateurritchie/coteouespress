<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\AcceptHeader;


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

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($user_id){
            $request->query->set('id',intval($user_id));
        }

    	$params = $request->query->all();
    	$data = $rep->search($params,$limit,$offset);

    	$user = new User();
    	$form = $this->createForm(UserAdminRegistrationType::class,$user,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

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

        // requete ajax
        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            
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
            $view = null;

            if(is_array($data)){
                $view = $this->render('admin/user/item-render.html.twig',array(
                    "data"=>$data,
                ));
            }
            else{

                $form2 = $this->createForm(UserAdminRegistrationType::class,$data,[
                    'usr_roles'=>$data->getUroles(),
                    'upload_dir' => $this->getParameter('public_upload_directory'),
                ]);
                
                $formView = $form2->createView();

                $view = $this->render('admin/user/selected-view.html.twig',[
                    "data"=>$data,
                    "use_modal"=>"update",
                    "form"=>$formView,
                ]);
            }

            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                return $view;
            }
            
            if($view){
                $result['view'] = $view->getContent();
            }

            $json = json_encode($result);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/user/index.html.twig',array(
            "users"=>$data,
            "roles"=>$rep_role->findAll(),
    		"form"=>$form->createView()
    	));
    }

    


    /**
    * @Route("/{user_id}/grant-role", requirements={"user_id":"\d+"}, name="grant_role")
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
    * @Route("/{user_id}/revoke-role", requirements={"user_id":"\d+"}, name="revoke_role")
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

    /**
    * @Route("/email-registration-self-notice", name="email_registration_")
    */
    public function selfNoticeRegistrationAction(Request $request){
        return $this->render('admin/user/email/registration-self-notice.html.twig',[
            "user_type"=>"Producteur",
            "username"=>"Zacharie A. Assagou",
            "email"=>"zakeszako@yahoo.fr",
        ]);
    }




}
