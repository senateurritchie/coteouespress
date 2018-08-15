<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\CatalogType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CatalogTypeFixtures extends Fixture{

    public function load(ObjectManager $manager){

        /*$data = [
           "ARABOPHONE","CINEMA","CLOSED CIRCUIT","DOM TOM",
           "EDAN PAY TV","ESA","FSA","LUSOPHONE","SHORT FORMAT","VOD","WORLDWIDE"
        ];

        foreach ($data as $key => $el) {
            $e = new CatalogType();
            $e->setName($el);
            $manager->persist($e);
        }
        
        $manager->flush();*/
    }
}

