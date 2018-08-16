<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


use AppBundle\Entity\Catalog;
use AppBundle\Entity\Movie;

/**
* @Route("/catalogues", name="catalog_")
*/
class CatalogController extends Controller{
    /**
    * @Route("/watch-link/{token}/", name="watch_link", requirements={"token":"[\w-]+"})
    */
    public function watchLinkAction(Request $request,$token){

        if($request->headers->has('If-None-Match')){
            $response = new Response();
            $response->setNotModified();
            return $response;
        }

        $formattedData = [];
        $cFields = [];

        $path = [$this->getParameter('watch_link_dir')];
        $path[] = "$token.json";
        $path = implode("/", $path);

        // on lit directement dans le fichier
        if(file_exists($path) && is_file($path)){
            $data = json_decode(file_get_contents($path),true);
            $cFields = $data["catalogHeader"];
            $formattedData = $data['formattedData'];
        }
        else{
            throw $this->createNotFoundException("le catalogue désiré est introuvable");
        }

        $response = $this->render('catalogue/preview.html.twig',array(
            "formattedData"=>$formattedData,
        ));

        // on cache pour 1 mois
        $maxAge = 2592000; //2592000
        $response->setPublic();
        $response->setMaxAge($maxAge);
        $response->setSharedMaxAge($maxAge);

        $date = new \DateTime();
        $date->modify('+1 month');
        $response->setExpires($date);

        $response->setEtag(md5($response->getContent()));
        $response->setLastModified(new \DateTime());
        $response->isNotModified($request);

        return $response;
    }

}
