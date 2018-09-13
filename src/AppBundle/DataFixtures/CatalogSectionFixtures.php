<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\CatalogSection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CatalogSectionFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Afrique & Diaspora",
                "english_text"=>"Africa",
                "english_slug"=>"africa",
            ),
            array(
                "french_text"=>"Reste du monde",
                "english_text"=>"World",
                "english_slug"=>"world",
            ),
            array(
                "french_text"=>"Animation & Documentaire",
                "english_text"=>"Doc & Animation",
                "english_slug"=>"doc-animation",
            ),
        );

        foreach ($data as $key => $el) {
            $item = new CatalogSection();

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