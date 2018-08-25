<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
* @Route("/categories", name="category_")
*/
class CateroryController extends Controller
{
	/**
    * @Route("/{slug}", name="index", requirements={"slug":"[\w-]+"})
    */
    public function indexAction(Request $request,$slug){
    	$query = array_merge($request->query->all(),["category"=>$slug]);
    	return $this->forward('AppBundle:Movie:index',[],$query);
    }
}
