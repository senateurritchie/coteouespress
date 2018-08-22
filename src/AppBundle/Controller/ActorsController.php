<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Movie;

/**
* @Route("/acteurs", name="actor_")
*/
class ActorsController extends Controller
{
	/**
    * @Route("/{slug}", requirements={"slug":"[\w-]+"}, name="index")
    */
    public function indexAction($slug){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Actor::class);
    	$rep_m = $em->getRepository(Movie::class);

    	if(!($item = $rep->findOneBySlug($slug))){
    		throw $this->createNotFoundException("Acteur introuvable");
    	}

    	$movies = $rep_m->search(["actor"=>$item->getId(),"order_id"=>"DESC"]);

    	return $this->render('director/index.html.twig',[
    		"profil"=>$item,
    		"movies"=>$movies,
    	]);

    }


}
