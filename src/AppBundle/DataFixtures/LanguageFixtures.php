<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixtures extends Fixture{

    public function load(ObjectManager $manager){

        /*$repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $lang_1 = new Language();
        $lang_1->setName("French");
        $repository->translate($lang_1, 'name', 'fr', 'Français');
        $repository->translate($lang_1, 'slug', 'fr', 'francais');
        $lang_1->setCreateAt(new \Datetime());
        $manager->persist($lang_1);


        $lang_2 = new Language();
        $lang_2->setName("English");
        $repository->translate($lang_2, 'name', 'fr', 'Anglais');
        $repository->translate($lang_2, 'slug', 'fr', 'anglais');
        $lang_2->setCreateAt(new \Datetime());
        $manager->persist($lang_2);
        
        $manager->flush();*/
    }
}