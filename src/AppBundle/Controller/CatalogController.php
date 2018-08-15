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

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Catalog::class);

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Catalog::class);

        if(!($catalog = $rep->findOneByToken($token))){
            throw $this->createNotFoundException('le catalogue est introuvable');
        }

        $params = $catalog->getCriteria();
        $rep = $em->getRepository(Movie::class);

        $limit = @$params['limit'] ? intval($params['limit']) : -1;
        $data = $rep->search($params, $limit);

        $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $formattedData = [];

        foreach ($data as $key => $el) {
            $item = [];

            $formatData = $el->getFormat();
            $formatDuration = null;
            $formatEps = null;

            if ($formatData){
                $e = explode("x", $formatData);
                $formatEps = $e[0];
                $formatDuration = $e[1];
            }

            $translations = $repository->findTranslations($el);
        
            $item = [
                "name"=>$el->getName(),
                "originalName"=>$el->getOriginalName(),
                "category"=>$el->getCategory()->getName(),
                "mention"=>$el->getMention(),
                "format"=>$formatData,
                "duration"=>$formatDuration,
                "episodeNbr"=>$formatEps,
                "producers"=>[],
                "genres"=>[],
                "countries"=>[],
                "directors"=>[],
                "casting"=>[],

                "yearStart"=>$el->getYearStart()?$el->getYearStart()->format('Y'):null,
                "yearEnd"=>$el->getYearEnd()?$el->getYearEnd()->format('Y'):null,

                "synopsis"=>$el->getSynopsis(),
                "tagline"=>$el->getTagline(),
                "logline"=>$el->getLogline(),

                "synopsis_en"=>@$translations["en"]["synopsis"],
                "tagline_en"=>@$translations["en"]["tagline"],
                "logline_en"=>@$translations["en"]["logline"],

                "synopsis_ar"=>@$translations["ar"]["synopsis"],
                "tagline_ar"=>@$translations["ar"]["tagline"],
                "logline_ar"=>@$translations["ar"]["logline"],

                "reward"=>$el->getReward(),
                "award"=>$el->getAward(),
                "audience"=>$el->getAudience(),
                "image"=>$el->getPortraitImg(),
                "language"=>$el->getLanguage()?$el->getLanguage()->getName():null,

                "versions"=>[],
                "trailer"=>$el->getTrailer(),
                "episode1"=>$el->getEpisode1(),
                "episode2"=>$el->getEpisode2(),
                "episode3"=>$el->getEpisode3(),
            ];

            // les produceurs
            foreach ($el->getProducers() as $e) {
                $item["producers"][] = $e->getProducer()->getName();
            }
            // les genres
            foreach ($el->getGenres() as $e) {
                $item["genres"][] = $e->getGenre()->getName();
            }
            // les countries
            foreach ($el->getCountries() as $e) {
                $item["countries"][] = $e->getCountry()->getName();
            }
            // les directors
            foreach ($el->getDirectors() as $e) {
                $item["directors"][] = $e->getDirector()->getName();
            }
            // les acteurs
            foreach ($el->getActors() as $e) {
                $item["casting"][] = $e->getActor()->getName();
            }
            // les versions
            foreach ($el->getLanguages() as $e) {
                $item["versions"][] = $e->getLanguage()->getName();
            }

            $formattedData[] = $item;
        }


        return $this->render('catalogue/preview.html.twig',array(
            "formattedData"=>$formattedData,
        ));

    }

}
