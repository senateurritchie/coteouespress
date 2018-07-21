<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;

class UserFixtures extends Fixture{

	/**
	* @var UserPasswordEncoderInterface 
	*/
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager){

        /*$rep_role = $manager->getRepository(Role::class);

        // on cree le super admin
    	$user = new User();
        $user->setUsername("zakeszako");
        $user->setEmail("zakeszako@test.coa");
        $plainPassword = 'zack-coa';
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $user->setCreateAt(new \Datetime());

        // role de super admin
        $role = $rep_role->findOneByLabel("ROLE_SUPER_ADMIN");
        $userrole = new UserRole();
        $userrole->setUser($user);
        $userrole->setRole($role);
        $userrole->setCreateAt(new \Datetime());

        $manager->persist($user);
        $manager->persist($userrole);


        // on cree les autres utilistateurs
        $rolesData = $rep_role->findBy(["type"=>"role"]);
        $roles = array_filter($rolesData,function($el){
            return ($el->getType() == "role" && $el->getLabel() != "ROLE_SUPER_ADMIN");
        });
        $roles = array_values($roles);

        for ($i = 0; $i < 25; $i++) {
        	$username = 'user'.$i.uniqid();
            $user = new User();
            $user->setUsername($username);
            $user->setEmail("$username@test.coa");
            $plainPassword = 'pwd-coa';
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setCreateAt(new \Datetime());

            $role = $roles[mt_rand(0,count($roles)-1)];
            $userrole = new UserRole();
            $userrole->setUser($user);
            $userrole->setRole($role);
            $userrole->setCreateAt(new \Datetime());

            $manager->persist($user);
            $manager->persist($userrole);
        }
        $manager->flush();*/
    }
}