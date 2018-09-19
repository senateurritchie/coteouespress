<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProducerFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /* $data = ["1611 PROD","AUDIO VISUAL FIRST","CLEM","Clemriches Media Consult","DO MEDIA LTD","MOLEHILL AGENCIES","ROYAL ARTS ACADEMY","STONE","WALE ADENUGA PRODUCTIONS","WHITESTONE CINEMA",
        ];

        foreach ($data as $key => $el) {
            $e = new Producer();
            $e->setName($el);
            $manager->persist($e);
        }
        
        $manager->flush();*/
    }
}

