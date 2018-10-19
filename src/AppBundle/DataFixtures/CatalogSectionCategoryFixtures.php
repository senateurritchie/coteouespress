<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\CatalogSectionCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CatalogSectionCategoryFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Animations",
                "english_text"=>"Animated",
                "english_slug"=>"animated",
            ),
            array(
                "french_text"=>"Documentaires",
                "english_text"=>"Documentary",
                "english_slug"=>"documentary",
            ),
            array(
                "french_text"=>"Films - Telefilms & Mini Series",
                "english_text"=>"Feature Film - Tv Movies & Mini Series",
                "english_slug"=>"feature-film-tv-movies-mini-series",
            ),
            array(
                "french_text"=>"Series",
            ),
             array(
                "french_text"=>"Telenovelas",
            ),
            array(
                "french_text"=>"Telenovelas & Soapies",
            ),
            array(
                "french_text"=>"MAGAZINE - TALK & SHORT",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"NOLLY DTV",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"NIGERIAN FEATURE FILMS",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"INSPIRATIONAL FAITH BASE MOVIES",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"SOUTH AFRICAN DTV",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"AFRICAN DIAPORA MOVIES",
                "english_text"=>"",
                "english_slug"=>"",
            ),
        );

        foreach ($data as $key => $el) {
            $item = new CatalogSectionCategory();

            $item->setName($el["french_text"]);
            if(@$el["english_text"]){
                $repository->translate($item, 'name', 'en', $el["english_text"]);
                $repository->translate($item, 'slug', 'en', $el["english_slug"]);
            }
            $manager->persist($item);
        }

        $manager->flush();*/
    }
}