<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenreFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /*

      $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Action",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Animation",
                "english_text"=>"Animated",
                "english_slug"=>"animated",
            ),
            array(
                "french_text"=>"Arts martiaux",
                "english_text"=>"Martial Arts",
                "english_slug"=>"martial-arts",
            ),
            array(
                "french_text"=>"Astrologie",
                "english_text"=>"astrology",
                "english_slug"=>"Astrology",
            ),
            array(
                "french_text"=>"Aventure",
                "english_text"=>"Adventure",
                "english_slug"=>"adventure",
            ),
            array(
                "french_text"=>"Biographie",
                "english_text"=>"Biography",
                "english_slug"=>"biography",
            ),
            array(
                "french_text"=>"Comedie",
                "english_text"=>"Comedy",
                "english_slug"=>"comedy",
            ),
            array(
                "french_text"=>"Cuisine",
                "english_text"=>"Cooking",
                "english_slug"=>"cooking",
            ),
            array(
                "french_text"=>"Culture",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Divertissement",
                "english_text"=>"Entertainment",
                "english_slug"=>"entertainment",
            ),
            array(
                "french_text"=>"Documentaire",
                "english_text"=>"Documentary",
                "english_slug"=>"documentary",
            ),
            array(
                "french_text"=>"Drame",
                "english_text"=>"Drama",
                "english_slug"=>"drama",
            ),
            array(
                "french_text"=>"Education",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Enfant",
                "english_text"=>"Child",
                "english_slug"=>"child",
            ),
            array(
                "french_text"=>"Enquête",
                "english_text"=>"Investigation",
                "english_slug"=>"investigation",
            ),
            array(
                "french_text"=>"Environnement",
                "english_text"=>"Environment",
                "english_slug"=>"environment",
            ),
            array(
                "french_text"=>"Epouvante",
                "english_text"=>"Epouvane",
                "english_slug"=>"epouvane",
            ),
            array(
                "french_text"=>"Famille",
                "english_text"=>"Family",
                "english_slug"=>"family",
            ),
             array(
                "french_text"=>"Fantaisie",
                "english_text"=>"Fantaisy",
                "english_slug"=>"fantaisy",
            ),
            array(
                "french_text"=>"Fantastique",
                "english_text"=>"Fantastic",
                "english_slug"=>"fantastic",
            ),
            array(
                "french_text"=>"Fiction",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Foi",
                "english_text"=>"Faith",
                "english_slug"=>"faith",
            ),
            array(
                "french_text"=>"Guerre",
                "english_text"=>"War",
                "english_slug"=>"war",
            ),
            array(
                "french_text"=>"Histoire",
                "english_text"=>"History",
                "english_slug"=>"history",
            ),
            array(
                "french_text"=>"Horreur",
                "english_text"=>"Horror",
                "english_slug"=>"horror",
            ),
            array(
                "french_text"=>"Humour",
                "english_text"=>"Humor",
                "english_slug"=>"humor",
            ),
            array(
                "french_text"=>"Jeunesse",
                "english_text"=>"Teen",
                "english_slug"=>"teen",
            ),
            array(
                "french_text"=>"Justice",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Medical",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Mode",
                "english_text"=>"Fashion",
                "english_slug"=>"fashion",
            ),
            array(
                "french_text"=>"Musique",
                "english_text"=>"Music",
                "english_slug"=>"music",
            ),
            array(
                "french_text"=>"Mystère",
                "english_text"=>"Mystery",
                "english_slug"=>"mystery",
            ),
            array(
                "french_text"=>"Police",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Politique",
                "english_text"=>"Politic",
                "english_slug"=>"politic",
            ),
            array(
                "french_text"=>"Réligion",
                "english_text"=>"Religion",
                "english_slug"=>"religion",
            ),
            array(
                "french_text"=>"Romance",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Science",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Sci-Fi",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Sitcom",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Social",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Société",
                "english_text"=>"Society",
                "english_slug"=>"society",
            ),
            array(
                "french_text"=>"Sport",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Suspense",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Thriller",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Vengeance",
                "english_text"=>"Revenge",
                "english_slug"=>"revenge",
            ),
            array(
                "french_text"=>"Western",
                "english_text"=>"",
                "english_slug"=>"",
            ),
            array(
                "french_text"=>"Santé",
                "english_text"=>"Health",
                "english_slug"=>"health",
            ),
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
            ),
            array(
                "french_text"=>"Spiritualité",
                "english_text"=>"Spirituality",
                "english_slug"=>"spirituality",
            ),
            array(
                "french_text"=>"Urbain",
                "english_text"=>"Urban",
                "english_slug"=>"urban",
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