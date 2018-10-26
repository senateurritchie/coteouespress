<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
    public function profileAction(Request $request,TranslatorInterface $translator){
        $em = $this->getDoctrine()->getManager();

        $item = $this->getUser();

        $form = $this->createForm(UserProfilType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

        $oldImage = basename($item->getImage());

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

            $this->addFlash('notice-success',$translator->trans("modification éffectuée avec success",array(),"account-setting"));
            return $this->redirectToRoute("account_profile");
        }

    	return $this->render('account/profile.html.twig',["form"=>$form->createView()]);
    }

    /**
    * @Route("/password/update", name="pwd_update")
    * @Method("POST")
    */
    public function pwdUpdateAction(Request $request, UserPasswordEncoderInterface $encoder,TranslatorInterface $translator){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $old_pwd = trim($request->request->get('_old_pwd'));
        $new_pwd = trim($request->request->get('_new_pwd'));
        $new_cpwd = trim($request->request->get('_new_cpwd'));

        
        if(!$old_pwd){
            $this->addFlash('notice-error',$translator->trans("veuillez saisir l'ancien mot de passe",array(),"account-setting"));
        }
        else if(!$new_pwd || !$new_cpwd){
            $this->addFlash('notice-error',$translator->trans("veuillez saisir le nouveau mot de passe",array(),"account-setting"));
        }
        else if(!$encoder->isPasswordValid($user, $old_pwd)){
            $this->addFlash('notice-error',$translator->trans("le mot de passe donné est incorrect. êtes-vous vraiment le depositaire de ce compte ?",array(),"account-setting"));
        }
        else if(strcmp($new_pwd, $new_cpwd) != 0){
            $this->addFlash('notice-error',$translator->trans("les mots de passe donnés sont différents",array(),"account-setting"));
        }
        else{
            $encoded = $encoder->encodePassword($user, $new_pwd);
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice-success',$translator->trans("modification éffectuée avec success",array(),"account-setting"));
        }

        return $this->redirectToRoute("account_settings");
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
