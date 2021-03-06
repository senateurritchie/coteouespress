<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
* @Route("/trailer", name="trailer_")
*/
class TrailerController extends Controller{
	/**
    * @Route("/{slug}", requirements={"slug":"\w+"}, name="index")
    */
    public function indexAction($slug){
    	return $this->render('trailer/index.html.twig');
    }
}
