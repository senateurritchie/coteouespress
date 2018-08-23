<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/account/download", name="account_download_")
*/
class AccountDownloadController extends Controller{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	return $this->render('account/download/index.html.twig');
    }
}