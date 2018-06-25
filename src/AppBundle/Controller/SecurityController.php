<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use AppBundle\Entity\User;


/**
* @Route("/security", name="security_")
*/
class SecurityController extends Controller{
	/**
    * @Route("/{_locale}/login", requirements={"_locale":"fr|en"}, defaults={"_locale":"fr"}, name="login")
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
    * @Route("/logout", name="logout")
    */
    public function logoutAction(){
    	
    	return $this->redirectToRoute('homepage');
    }
}
