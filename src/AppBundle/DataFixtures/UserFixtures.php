<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture{

	/**
	* @var UserPasswordEncoderInterface 
	*/
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager){

    	// super admin
    	$user = new User();
        $user->setUsername("zakeszako");
        $user->setEmail("zakeszako@test.coa");
        $plainPassword = 'zack-coa';
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $user->setRoles(array("ROLE_SUPER_ADMIN"));
        $manager->persist($user);

        $roles = ["ROLE_ADMIN","ROLE_TRANSLATOR","ROLE_CATALOGUE","ROLE_CATALOGUE_UPDATE","ROLE_CATALOGUE_REMOVE","ROLE_CATALOGUE_INSERT","ROLE_SUBSCRIBER"];

        for ($i = 0; $i < 20; $i++) {
        	$username = 'user'.$i.uniqid();
            $user = new User();
            $user->setUsername($username);
            $user->setEmail("$username@test.coa");
            $plainPassword = 'pwd-coa';
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setRoles(array($roles[mt_rand(0,count($roles)-1)]));

            $manager->persist($user);
        }
        $manager->flush();
    }
}