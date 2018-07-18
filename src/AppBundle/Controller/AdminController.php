<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\User;

/**
* @Route("/admin", name="admin_")
*/
class AdminController extends Controller
{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	return $this->render('admin/index.html.twig');
    }

    /**
    * @Route("/profil", name="profil")
    */
    public function profilAction(){
    	return $this->render('admin/profil/index.html.twig');
    }

    /**
    * @Route("/inbox", name="inbox")
    */
    public function inboxAction(){
    	return $this->render('admin/inbox/index.html.twig');
    }

    public function renderUserList(){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(User::class);
        $users = $rep->findBy([],["id"=>"desc"],8);
        return $this->render('admin/accueil/user-list.html.twig',['users'=>$users]);
    }
}
