<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



/**
* @Route("/categories", name="category_")
*/
class CateroryController extends Controller
{
	/**
    * @Route("/{slug}", name="index", requirements={"slug":"[\w-]+"})
    */
    public function indexAction($slug){
    	return $this->forward('AppBundle:Movie:index',[],['category'=>$slug]);
    }
}
