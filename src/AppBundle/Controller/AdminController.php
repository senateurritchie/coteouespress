<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\User;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Department;
use AppBundle\Entity\WebsiteMail;
use AppBundle\Entity\Catalog;


use AppBundle\Form\UserProfilType;

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
    public function profilAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $item = $this->getUser();
        $oldImage = $item->getImage();

        $form = $this->createForm(UserProfilType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

        $form->handleRequest($request);

        foreach ($form->all() as $child) {
            if (!$child->isValid() && count($child->getErrors())) {
                $error = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                $this->addFlash('notice-error',$error);
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $em->merge($item);

            if(!$item->getImage() && $oldImage){
                $item->setImage($oldImage);
            }

            if($oldImage && $item->getImage() && $item->getImage() != $oldImage){
                $path = $this->getParameter('public_upload_directory').'/'.$oldImage;
                unlink($path);
            }

            $em->flush();

            $this->addFlash('notice-success',"modification éffectuée avec success");
            return $this->redirectToRoute("admin_profil",['tab'=>"settings"]);
        }

    	return $this->render('admin/profil/index.html.twig',[
            "form"=>$form->createView()
        ]);
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
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Catalog::class);
        $lists = $rep->findBy([],["id"=>"desc"],4);
        return $this->render('admin/accueil/catalog-todo-list.html.twig',['lists'=>$lists]);
    }

    public function renderUnprocessedWebmail(){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(WebsiteMail::class);
        return new Response($rep->count(["is_processed"=>0]));
    }
}
