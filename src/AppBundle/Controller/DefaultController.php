<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller{
    /**
     * @Route("/{_locale}/", requirements={"_locale":"fr|en"}, defaults={"_locale":"fr"}, name="homepage")
     */
    public function indexAction(Request $request,$_locale = 'fr'){
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/{_locale}/l-entreprise/", name="presentation")
     */
    public function presentationAction(Request $request,$_locale = 'fr'){
        return $this->render('default/l-entreprise.html.twig');
    }

    /**
    * @Route("/switch-language/{_locale}/", requirements={"_locale":"fr|en"}, defaults={"_locale":"fr"}, name="switch_language")
    */
    public function switchLanguageAction(Request $request,$_locale = 'fr'){
        
    }
}
