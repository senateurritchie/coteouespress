<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenreFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Jeu télévisé",
                "english_text"=>"GAME SHOW",
                "english_slug"=>"game-show",
            ),
            array(
                "french_text"=>"Spectacle",
                "english_text"=>"TALK SHOW",
                "english_slug"=>"talk-show",
            ),
            array(
                "french_text"=>"Amour",
                "english_text"=>"Love",
                "english_slug"=>"love",
            ),
            array(
                "french_text"=>"Télé Réalité",
                "english_text"=>"REALITY SHOW",
                "english_slug"=>"reality-show",
            )
        );



        foreach ($data as $key => $el) {
            $item = new Genre();

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