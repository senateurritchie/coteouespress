<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/account/producer", name="account_producer_")
*/
class AccountProducerController extends Controller{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	return $this->render('account/producer/index.html.twig');
    }
}
