<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Producer;
use AppBundle\Entity\Movie;

/**
* @Route("/producers", name="producer_")
*/
class ProducersController extends Controller{
	/**
    * @Route("/{slug}", requirements={"slug":"[\w-]+"}, name="index")
    */
    public function indexAction($slug){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Producer::class);
    	$rep_m = $em->getRepository(Movie::class);

    	if(!($item = $rep->findOneBySlug($slug))){
    		throw $this->createNotFoundException("Producteur introuvable");
    	}

    	$movies = $rep_m->search(["producer"=>$item->getId(),"order_id"=>"DESC"]);

    	return $this->render('director/index.html.twig',[
    		"profil"=>$item,
    		"movies"=>$movies,
    	]);
    }
}
