<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserTypeFixtures extends Fixture{

    public function load(ObjectManager $manager){

        /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "french_text"=>"Producteur",
                "english_text"=>"Producer",
                "english_slug"=>"producer",
            ),
            array(
                "french_text"=>"Réalisateur",
                "english_text"=>"Director",
                "english_slug"=>"director",
            ),
            array(
                "french_text"=>"Client",
                "english_text"=>"Customer",
                "english_slug"=>"customer",
            ),
            array(
                "french_text"=>"Employé",
                "english_text"=>"Employee",
                "english_slug"=>"employee",
            ),
            array(
                "french_text"=>"Autre",
                "english_text"=>"other",
                "english_slug"=>"other",
            ),
        );

        foreach ($data as $key => $el) {
            $item = new UserType();

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

