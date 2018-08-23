<?php
namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutListener implements LogoutHandlerInterface{

    protected $em;

    public function __construct(EntityManager  $em){
        $this->em = $em;
    }

    public function logout(Request $request, Response $response, TokenInterface $uToken){
        $user = $uToken->getUser();
        $token = $uToken->getAttribute("coa_user_token");

        $rep = $this->em->getRepository(\AppBundle\Entity\Login::class);
        if(($e = $rep->findOneByToken($token))){

            $login = new \AppBundle\Entity\Login();
            $login->setUser($e->getUser());
            $login->setCountry($e->getCountry());
            $login->setAction(\AppBundle\Entity\Login::ACTION_LOGOUT);
            $login->setIp($e->getIp());
            $login->setCity($e->getCity());
            $login->setLat($e->getLat());
            $login->setLon($e->getLon());
            $login->setDevice($e->getDevice());
            $login->setUserAgent($e->getUserAgent());
            $login->setToken($token);
            $this->em->persist($login);
            $this->em->flush();
        }

    }
}