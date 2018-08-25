<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
* @Route("/genres", name="genre_")
*/
class GenreController extends Controller
{
    /**
    * @Route("/{slug}", name="index", requirements={"slug":"[\w-]+"})
    */
    public function indexAction(Request $request,$slug){
    	$query = array_merge($request->query->all(),["genre"=>$slug]);
    	return $this->forward('AppBundle:Movie:index',[],$query);
    }
}
