<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Director;
use AppBundle\Entity\Movie;

/**
* @Route("/director", name="director_")
*/
class DirectorController extends Controller{
	/**
    * @Route("/{slug}", requirements={"slug":"[\w-]+"}, name="index")
    */
    public function indexAction($slug){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Director::class);
    	$rep_m = $em->getRepository(Movie::class);

    	if(!($item = $rep->findOneBySlug($slug))){
    		throw $this->createNotFoundException("Réalisateur introuvable");
    	}

    	$movies = $rep_m->search(["director"=>$item->getId(),"order_id"=>"DESC"]);

    	return $this->render('director/index.html.twig',[
    		"profil"=>$item,
    		"movies"=>$movies,
    	]);
    }
}
