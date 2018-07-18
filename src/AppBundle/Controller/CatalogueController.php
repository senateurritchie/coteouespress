<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\CatalogType;
use AppBundle\Entity\Catalog;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Actor;

/**
* @Route("/programmes", name="catalogue_")
*/
class CatalogueController extends Controller{
    /**
    * @Route("/{slug}", name="index", requirements={"slug":"([\w-]+)?"})
    */
    public function indexAction(Request $request,$slug=null){

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Movie::class);


    	if($slug){
    		if(!($programme = $rep->findOneBy(array("slug"=>$slug)))){
    			throw $this->createNotFoundException("Le programme recherché est introuvable");
    		}

             //  on charge les acteurs
            $rep = $em->getRepository(Actor::class);
            $actors = $rep->findBy([],["id"=>"desc"],6);

    		return $this->render('catalogue/movie-single.html.twig',array(
                "programme"=>$programme,
                "actors"=>$actors,
    		));
    	}

    	$catalogue = new Catalog();
    	$form = $this->createForm(CatalogType::class,$catalogue);
    	$params = $request->query->all();

    	$params["locale"] = $request->getLocale();
    	$programmes = $rep->search($params);

    	//  on charge les categories
    	$rep = $em->getRepository(Category::class);
    	$categories = $rep->findBy([],["name"=>"asc"]);

    	//  on charge les genres
    	$rep = $em->getRepository(Genre::class);
    	$genres = $rep->findBy([],["name"=>"asc"]);

    	return $this->render('catalogue/search.html.twig',array(
    		"form"=>$form->createView(),
    		"programmes"=>$programmes,
    		"categories"=>$categories,
    		"genres"=>$genres,
    	));
    }

}
