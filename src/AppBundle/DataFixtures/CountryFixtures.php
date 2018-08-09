<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Country;

class CountryFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /* $rep = $manager->getRepository(Country::class);

        if(($data = $rep->findBy([],["id"=>"asc"]))){
            foreach ($data as $key => $el) {
                $el->setSlug($el->getName());
            }
        }

        $manager->flush();*/
    }
}