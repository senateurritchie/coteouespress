<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use AppBundle\Entity\User;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;
use AppBundle\Entity\PasswordReset;

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
            $em->flush();

            $this->addFlash("notice-success",1);
            return $this->redirectToRoute("security_registration");
        }

        return $this->render('security/registration.html.twig', array(
            "form"=>$form->createView(),
        ));
    }

    /**
    * @Route("/password/reset", name="pwd_rst")
    */
    public function passwordResetAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);

        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('account_index');
        }

        if($request->isMethod('POST')){
            $email = $request->request->get('email');

            if(!($user = $rep->findOneByEmail($email))){
                $this->addFlash("notice-error","Désolé cette adresse email n'existe pas, avez-vous déja un compte Côte Ouest ?");
            }
            else{
                $this->addFlash("notice-success","Un mail vous a été adressé pour reinitialiser votre mot de passe.");

                $item = new PasswordReset();
                $item->setUser($user);
                $em->persist($item);
                $em->flush();
            }

            return $this->redirectToRoute("security_pwd_rst");       
        }
        return $this->render('security/password-reset.html.twig');
    }

    /**
    * @Route("/password/renew/{token}", name="pwd_renew")
    */
    public function passwordRenewAction(Request $request,$token){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(PasswordReset::class);
        $item = $rep->findOneByToken($token);

        $encoder = $this->container->get('security.password_encoder');

        if(!$item){
            throw $this->createNotFoundException("Nous ne pouvons pas traiter cette demande");
        }

        $user = $item->getUser();

        if($request->isMethod('POST')){
            $pwd = strip_tags(trim($request->request->get('pwd')));
            $cpwd = strip_tags(trim($request->request->get('cpwd')));
            $has_error = false;

            if(!$cpwd || !$pwd){
                $this->addFlash("notice-error","Veuillez saisir correctement les mots de passes");
                $has_error = true;
            }
            else if($cpwd != $pwd){
                $this->addFlash("notice-error","les mots de passes sont différents");
                $has_error = true;
            }
            else if(strlen($pwd) < 8){
                $this->addFlash("notice-error","pour la securité de votre compte, le mot de passe doit avoir une longueur d'au moins 8 caractères");
                $has_error = true;
            }

            if($has_error){
                return $this->redirectToRoute("security_pwd_renew",["token"=>$token]);
            }

            $encoded = $encoder->encodePassword($user, $pwd);
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            $this->get("security.token_storage")->setToken($token);

            // Fire the login event
            // Logging the user in above the way we do it doesn't do this automatically

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute("account_index");
        }

        if($item->getEmailVerified()){
            $item->setEmailVerified(1);
            $em->flush();
        }

        return $this->render('security/password-renew.html.twig');
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
            // $user->setState("activate");
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
