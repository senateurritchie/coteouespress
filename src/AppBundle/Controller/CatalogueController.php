<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\AcceptHeader;

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

        $limit = intval($request->query->get('limit',20));
        $offset = intval($request->query->get('offset',0));

        $limit = $limit > 20 ? 20 : $limit;
        $offset = $offset < 0 ? 0 : $offset;

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
    	$data = $rep->search($params,$limit,$offset);

        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                $charset = $item->getAttribute('charset', 'utf-8');
                $quality = $item->getQuality();

                return $this->render('catalogue/search-movies-render.html.twig',array(
                    "programmes"=>$data,
                ));
            }

            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("programme introuvable");
                }
                $data = $data[0];
            }

            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);
        

            $json = json_encode($json);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


    	//  on charge les categories
    	$rep = $em->getRepository(Category::class);
    	$categories = $rep->findBy([],["name"=>"asc"]);

    	//  on charge les genres
    	$rep = $em->getRepository(Genre::class);
    	$genres = $rep->findBy([],["name"=>"asc"]);

    	return $this->render('catalogue/search.html.twig',array(
    		"form"=>$form->createView(),
    		"programmes"=>$data,
    		"categories"=>$categories,
    		"genres"=>$genres,
    	));
    }

}
