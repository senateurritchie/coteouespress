<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\UserProfilType;

/**
* @Route("/account", name="account_")
*/
class AccountController extends Controller
{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	return $this->render('account/index.html.twig');
    }


    /**
    * @Route("/settings", name="settings")
    */
    public function settingsAction(){
    	return $this->render('account/settings.html.twig');
    }

    /**
    * @Route("/inbox", name="inbox")
    */
    public function inboxAction(){
    	return $this->render('account/inbox.html.twig');
    }

    /**
    * @Route("/conditions-generales-d-utilisation", name="cgu")
    */
    public function cguAction(){
        return $this->render('account/cgu.html.twig');
    }

    /**
    * @Route("/politique-de-confidentialite", name="privacy_policy")
    */
    public function privacyPolicyAction(){
        return $this->render('account/privacy-policy.html.twig');
    }

    /**
    * @Route("/profile", name="profile")
    */
    public function profileAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $item = $this->getUser();

        $form = $this->createForm(UserProfilType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

        $oldImage = $item->getImage();

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
                $path = $this->getParameter('public_upload_directory').'/'.basename($oldImage);
                unlink($path);
            }

            $em->flush();

            $this->addFlash('notice-success',"modification éffectuée avec success");
            return $this->redirectToRoute("account_profile");
        }

    	return $this->render('account/profile.html.twig',["form"=>$form->createView()]);
    }



	/**
    * @Route("/checker", name="checker")
    */
    public function checkerAction(){

    	/*if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
        	throw new AccessDeniedException('Unable to access this page!');
    	}*/

    	if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_TRANSLATOR') || $this->isGranted('ROLE_CATALOGUE')){

    		return $this->redirectToRoute('admin_index');

    	}
    	else if($this->isGranted('ROLE_SUBSCRIBER')){
    		return $this->redirectToRoute('account_index');
    	}

    	return $this->redirectToRoute('homepage');
    }
}
