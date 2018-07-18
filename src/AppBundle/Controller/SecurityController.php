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


/**
* @Route("/security", name="security_")
*/
class SecurityController extends Controller{
	/**
    * @Route("/login", name="login")
    */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils,$_locale = 'fr'){

    	if($this->isGranted('IS_AUTHENTICATED_FULLY')){
    		return $this->redirectToRoute('homepage');
    	}

    	// get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

	    return $this->render('security/login.html.twig', array(
	        'last_username' => $lastUsername,
	        'error'         => $error,
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
