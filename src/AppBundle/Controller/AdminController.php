<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\User;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Department;
use AppBundle\Entity\WebsiteMail;

/**
* @Route("/admin", name="admin_")
*/
class AdminController extends Controller
{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();

        $rep = $em->getRepository(User::class);
        $rep_movie = $em->getRepository(Movie::class);
        $rep_webmail = $em->getRepository(WebsiteMail::class);

    	return $this->render('admin/index.html.twig',array(
            "stats"=>array(
                "user"=>$rep->count(),
                "movie"=>$rep_movie->count(),
                "mail_unprocessed"=>$rep_webmail->count(["is_processed"=>0]),
                "mail_processed"=>$rep_webmail->count(["is_processed"=>1]),
            )
        ));
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

    public function renderMovieList(){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $movies = $rep->findBy([],["id"=>"desc"],4);
        return $this->render('admin/accueil/movie-list.html.twig',['movies'=>$movies]);
    }

    public function renderDepartmentList(){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Department::class);
        $departments = $rep->findBy([],["name"=>"asc"]);
        return $this->render('admin/accueil/department-list.html.twig',['departments'=>$departments]);
    }

     public function renderCatalogTodoList(){
        /*$em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);*/
        $lists = [];
        return $this->render('admin/accueil/catalog-todo-list.html.twig',['lists'=>$lists]);
    }
}
