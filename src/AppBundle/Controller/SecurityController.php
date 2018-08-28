<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use AppBundle\Entity\User;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;

use AppBundle\Form\UserRegistrationType;

/**
* @Route("/security", name="security_")
*/
class SecurityController extends Controller{
	/**
    * @Route("/login", name="login")
    */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils){
        
        //$em = $this->getDoctrine()->getManager();
        //$rep_movie = $em->getRepository(Movie::class);

    	if($this->isGranted('IS_AUTHENTICATED_FULLY')){
    		return $this->redirectToRoute('account_index');
    	}

    	// get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

	    return $this->render('security/login.html.twig', array(
	        'last_username' => $lastUsername,
	        'error'         => $error,
            //"inTheather"=>$rep_movie->findOneBy(['hasExclusivity'=>1,"isPublished"=>1]),
	    ));
    }

    /**
    * @Route("/registration", name="registration")
    */
    public function registrationAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $rep_movie = $em->getRepository(Movie::class);

        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('account_index');
        }

        $user = new User();

        $form = $this->createForm(UserRegistrationType::class,$user,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setState('pending');
            $em->persist($user);

            $rep = $em->getRepository(Role::class);
            if(($role = $rep->findOneBy(["label"=>"ROLE_SUBSCRIBER"]))){
                $userrole = new UserRole();
                $userrole->setUser($user);
                $userrole->setRole($role);
                $em->persist($userrole);
            }
            $em->flush();

            $this->addFlash("notice-success",1);
            $this->redirectToRoute("security_registration");
        }

        return $this->render('security/registration.html.twig', array(
            "inTheather"=>$rep_movie->findOneBy(['hasExclusivity'=>1,"isPublished"=>1]),
            "form"=>$form->createView(),
        ));
    }

    /**
    * @Route("/activate/{token}", name="activation")
    */
    public function activateAction(Request $request,$token){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);
        $user = $rep->findOneBySignUpToken($token);

        if(!$user){
            throw $this->createNotFoundException("Nous ne pouvons pas traiter cette demande");
        }

        if($user->getState() == "pending"){
            $user->setState("activate");
            $user->setEmailVerified(1);
            $em->persist($user);
            $em->flush();
        }

        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get("security.token_storage")->setToken($token);

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically

        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return $this->redirectToRoute("account_index");
    }

    /**
    * @Route("/logout", name="logout")
    */
    public function logoutAction(){
    	
    	return $this->redirectToRoute('homepage');
    }
}
