<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
* @Route("/account", name="account_")
*/
class AccountController extends Controller
{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	return $this->render('account/index.html.twig');
    }


    /**
    * @Route("/settings", name="settings")
    */
    public function settingsAction(){
    	return $this->render('account/settings.html.twig');
    }

    /**
    * @Route("/inbox", name="inbox")
    */
    public function inboxAction(){
    	return $this->render('account/inbox.html.twig');
    }

	/**
    * @Route("/checker", name="checker")
    */
    public function checkerAction(){




    	/*if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
        	throw new AccessDeniedException('Unable to access this page!');
    	}*/

    	if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_TRANSLATOR') || $this->isGranted('ROLE_CATALOGUE')){

    		return $this->redirectToRoute('admin_index');

    	}
    	else if($this->isGranted('ROLE_SUBSCRIBER')){
    		return $this->redirectToRoute('account_index');
    	}

    	return $this->redirectToRoute('homepage');
    }


}
