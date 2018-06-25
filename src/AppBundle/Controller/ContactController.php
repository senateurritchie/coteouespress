<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
* @Route("/{_locale}/nos-contacts",requirements={"_locale":"fr|en"}, defaults={"_locale":"fr"}, name="contact_")
*/
class ContactController extends Controller{
    /**
    * @Route("/", name="index")
    */
    public function indexAction($_locale = 'fr'){
        return $this->render('contact/index.html.twig');
    }

}
