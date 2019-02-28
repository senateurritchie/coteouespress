<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture{

    public function load(ObjectManager $manager){

        $data = [
           "FESCAPO","APEX","DISCOP"
        ];

        foreach ($data as $key => $el) {
            $e = new Event();
            $e->setName($el);
            $manager->persist($e);
        }
        
        $manager->flush();
    }
}

