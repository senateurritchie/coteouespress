<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Login;
use AppBundle\Entity\CatalogDownload;

/**
* @Route("/account/download", name="account_download_")
*/
class AccountDownloadController extends Controller{
	/**
    * @Route("/", name="index")
    */
    public function indexAction(){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(\AppBundle\Entity\CatalogStatic::class);


    	$catalogs = $rep->findBy([],["year"=>"DESC"],20);

    	return $this->render('account/download/index.html.twig',['catalogs'=>$catalogs]);
    }

    /**
    * @Route("/catalog/{token}", name="catalog", requirements={"token":"[\w-]+"})
    */
    public function downloadCatalogAction($token){

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(\AppBundle\Entity\CatalogStatic::class);

    	$dir = $this->getParameter('documents_directory');

    	if(!($catalog = $rep->findOneByToken($token))){
    		throw $this->createNotFoundException("Désolé ce catalogue est introuvable");
    	}

    	$path = $dir."/".$catalog->getFile();

    	if(file_exists($path) && is_file($path)){

    		$utoken = $this->container->get('security.token_storage')->getToken();
    		$l_token = $utoken->getAttribute('coa_user_token');

    		$rep = $em->getRepository(\AppBundle\Entity\Login::class);
    		if(($userSession = $rep->findOneByToken($l_token))){
    			$log = new CatalogDownload();
    			$log->setCatalog($catalog);
    			$log->setUserSession($userSession);
    			$em->persist($log);
    			$em->flush();
    		}

    		return $this->file($path);
    	}
    	
    	throw $this->createNotFoundException("Désolé ce cataloque n'est pas encore disponible en téléchargement");
    }
}
