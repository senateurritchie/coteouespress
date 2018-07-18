<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\WebsiteReferer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class WebsiteRefererFixtures extends Fixture{

    public function load(ObjectManager $manager){

        /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $data = array(
            array(
                "english_text"=>"I know the address of your site",
                "french_text"=>"Je connais l’adresse de votre site",
                "french_slug"=>"je-connais-l-adresse-de-votre-site",
            ),

            array(
                "english_text"=>"Search on google",
                "french_text"=>"Recherche sur google",
                "french_slug"=>"recherche-sur-google",
            ),

            array(
                "english_text"=>"Press release / article",
                "french_text"=>"Communiqué de presse / article",
                "french_slug"=>"communique-de-presse-article",
            ),

            array(
                "english_text"=>"Côte Ouest Newsletter",
                "french_text"=>"Newsletter de Côte Ouest",
                "french_slug"=>"newsletter-de-cote-ouest",
            ),

            array(
                "english_text"=>"Other",
                "french_text"=>"Autres",
                "french_slug"=>"autres",
            ),
        );

        foreach ($data as $key => $el) {
            $referer = new WebsiteReferer();
            $referer->setName($el["english_text"]);
            $repository->translate($referer, 'name', 'fr', $el["french_text"]);
            $repository->translate($referer, 'slug', 'fr', $el["french_slug"]);
            $referer->setCreateAt(new \Datetime());
            $manager->persist($referer);
        }
        
        $manager->flush();*/
    }
}