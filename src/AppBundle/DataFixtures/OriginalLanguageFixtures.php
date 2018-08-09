<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\OriginalLanguage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OriginalLanguageFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Français",
                "english_text"=>"French",
                "english_slug"=>"french",
            ),
            array(
                "french_text"=>"Anglais",
                "english_text"=>"English",
                "english_slug"=>"english",
            ),
            array(
                "french_text"=>"Portugais",
                "english_text"=>"Portuguese",
                "english_slug"=>"portuguese",
            ),
            array(
                "french_text"=>"Arabe",
                "english_text"=>"Arabic",
                "english_slug"=>"arabic",
            ),
            array(
                "french_text"=>"Turc",
                "english_text"=>"Turquish",
                "english_slug"=>"turquish",
            ),
            array(
                "french_text"=>"Haoussa",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Bambara",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Swahili",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Lingala",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Moré",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Afrikaner",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Indi",
                "english_text"=>null,
                "english_slug"=>null,
            ),
            array(
                "french_text"=>"Autre",
                "english_text"=>"Other",
                "english_slug"=>"other",
            ),
        );

        foreach ($data as $key => $el) {
            $item = new OriginalLanguage();

            $item->setName($el["french_text"]);
            if($el["english_text"]){
                $repository->translate($item, 'name', 'en', $el["english_text"]);
                $repository->translate($item, 'slug', 'en', $el["english_slug"]);
            }
            $manager->persist($item);
        }

        $manager->flush();*/
    }
}