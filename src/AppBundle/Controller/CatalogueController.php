<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
* @Route("/{_locale}/le-catalogue",requirements={"_locale":"fr|en"}, defaults={"_locale":"fr"}, name="catalogue_")
*/
class CatalogueController extends Controller{
    /**
    * @Route("/{slug}", name="index", requirements={"slug":"(\w+)?"})
    */
    public function indexAction($slug=null,$_locale = 'fr'){

    	if($slug){
    		return $this->render('catalogue/movie-single.html.twig');
    	}

    	return $this->render('catalogue/search.html.twig');
    }

}
