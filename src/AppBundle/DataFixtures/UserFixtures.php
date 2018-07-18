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
        $user->setCreateAt(new \Datetime());
        $manager->persist($user);

        $roles = [
            "ROLE_ADMIN",
            "ROLE_TRANSLATOR",
            "ROLE_CATALOG",
            "ROLE_SUBSCRIBER",
            "ROLE_SALER",
            "ROLE_PRODUCER",
            "ROLE_DIRECTOR",
            "ROLE_ACTOR",
            "ROLE_CREATOR",
        ];

        $privileges = [
            "ROLE_USER_READ",
            "ROLE_USER_INSERT",
            "ROLE_USER_PRIVIL_ADD",
            "ROLE_USER_PRIVIL_DEL",

            "ROLE_CATALOG_READ",
            "ROLE_CATALOG_UPDATE",
            "ROLE_CATALOG_REMOVE",
            "ROLE_CATALOG_INSERT",
            "ROLE_CATALOG_INSERT",

            "ROLE_PRODUCER_READ",
            "ROLE_PRODUCER_INSERT",

            "ROLE_SALER_READ",
            "ROLE_SALER_INSERT",

            "ROLE_TRANSLATOR_READ",
            "ROLE_TRANSLATOR_INSERT",
        ];

        for ($i = 0; $i < 20; $i++) {
        	$username = 'user'.$i.uniqid();
            $user = new User();
            $user->setUsername($username);
            $user->setEmail("$username@test.coa");
            $plainPassword = 'pwd-coa';
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setRoles(array($roles[mt_rand(0,count($roles)-1)]));
            $user->setCreateAt(new \Datetime());
            $manager->persist($user);
        }

        $manager->flush();
    }
}