<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Entity\Actor;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Director;

class DefaultController extends Controller{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
       
        //  on charge les programmes
        $programmes = $rep->findBy([],["id"=>"desc"],9);

        //  on charge les acteurs
        $rep = $em->getRepository(Actor::class);
        $actors = $rep->findBy([],["id"=>"desc"],6);

        //  on charge les realisateurs
        $rep = $em->getRepository(Director::class);
        $directors = $rep->findBy([],["id"=>"desc"],6);

        //  on charge les producteurs
        $rep = $em->getRepository(Producer::class);
        $producer = $rep->findOneBy([],["id"=>"desc"]);

        return $this->render('default/index.html.twig',array(
            "programmes"=>$programmes,
            "actors"=>$actors,
            "directors"=>$directors,
            "producer"=>$producer,
        ));
    }

    /**
     * @Route("/a-propos-de-nous/", name="presentation")
     */
    public function presentationAction(Request $request){
        return $this->render('default/l-entreprise.html.twig');
    }

    /**
    * @Route("/switch-language/{lang_dest}/", requirements={"lang_dest":"fr|en"}, name="switch_lang")
    */
    public function switchLanguageAction(Request $request,$lang_dest){
        $cookie = new Cookie("_locale",$lang_dest);
        $response = $this->redirect($request->server->get("HTTP_REFERER"));
        $response->headers->setCookie($cookie);
        return $response;
    }

    public function renderFooter(){
        $em = $this->getDoctrine()->getManager();

        //  on charge les categories
        $rep = $em->getRepository(Category::class);
        $categories = $rep->findBy([],["name"=>"asc"]);

        return $this->render('footer.html.twig',array(
            "categories"=>$categories,
        ));
    }
}
