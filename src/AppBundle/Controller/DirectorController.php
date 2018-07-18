<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
* @Route("/director", name="director_")
*/
class DirectorController extends Controller{
	/**
    * @Route("/{slug}", requirements={"slug":"[\w-]+"}, name="index")
    */
    public function indexAction($slug){
    	return $this->render('director/index.html.twig');
    }
}
