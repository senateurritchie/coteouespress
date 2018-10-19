<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixtures extends Fixture{

    public function load(ObjectManager $manager){

        /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');



        $data = array(
            array(
                "french_text"=>"Version Originale Allemande",
                "english_text"=>"Original German Version",
                "english_slug"=>"original-german-version",
            ),
            array(
                "french_text"=>"Version Originale Anglaise",
                "english_text"=>"Original English Version",
                "english_slug"=>"original-english-version",
            ),
            array(
                "french_text"=>"Version Originale Française",
                "english_text"=>"Original French Version",
                "english_slug"=>"original-french-version",
            ),
            array(
                "french_text"=>"Version Originale Portugaise",
                "english_text"=>"Original Portuguese Version",
                "english_slug"=>"original-portuguese-version",
            ),
            array(
                "french_text"=>"Version Originale Japonaise",
                "english_text"=>"Original Japanese Version",
                "english_slug"=>"original-japanese-version",
            ),
            array(
                "french_text"=>"Version Originale Muette",
                "english_text"=>"Original Mute Version",
                "english_slug"=>"original-mute-version",
            ),
            array(
                "french_text"=>"Version Originale Langue Imaginaire",
                "english_text"=>"Original Imaginary Language Version",
                "english_slug"=>"original-imaginary-language-version",
            ),
            array(
                "french_text"=>"VOST Anglais",
                "english_text"=>"English VOST",
                "english_slug"=>"english-vost",
            ),
            array(
                "french_text"=>"VOST Français",
                "english_text"=>"French VOST",
                "english_slug"=>"french-vost",
            ),
            array(
                "french_text"=>"Version Originale doublée Anglais",
                "english_text"=>"Original Version dubbed English",
                "english_slug"=>"original-version-dubbed-english",
            ),
            array(
                "french_text"=>"Version Originale doublée Français",
                "english_text"=>"Original version dubbed French",
                "english_slug"=>"original-version-dubbed-french",
            ),
            array(
                "french_text"=>"Version Originale doublée Portugais",
                "english_text"=>"Original version dubbed Portuguese",
                "english_slug"=>"original-version-dubbed-portuguese",
            ),
             array(
                "french_text"=>"Langue locale",
                "english_text"=>"Local language",
                "english_slug"=>"local-language",
            )
        );

        foreach ($data as $key => $el) {
            $item = new Language();

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