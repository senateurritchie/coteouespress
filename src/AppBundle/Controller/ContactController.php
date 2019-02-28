<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\WebsiteMailType;
use AppBundle\Entity\WebsiteMail;
use AppBundle\Entity\Department;



class ContactController extends Controller{
   /**
	* @Route("/nos-contacts/", name="contact_index")
	*/
    public function indexAction(Request $request, \Swift_Mailer $mailer){

    	$clientMail = new WebsiteMail();
    	$clientMail->setCreateAt(new \Datetime());

    	$form = $this->createForm(WebsiteMailType::class,$clientMail);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		if($request->request->get('referer_message')){
    			$rm = strip_tags(trim($request->request->get('referer_message')));
    			$clientMail->setRefererMessage($rm);
    		}

    		$department = $clientMail->getDepartment();
            $adminInfo = $this->getParameter("admin");


    		if(!$department){
	        	$department = new Department();
	        	$department->setName($adminInfo["department"]);
	        	$department->setEmail($adminInfo["email"]);
	        	$clientMail->setDepartment($department);
	        }

	        // message a envoyer au visiteur
    		$message = (new \Swift_Message($clientMail->getSubject()))
	        ->setFrom([$department->getEmail()=>"Côte Ouest Audiovisuel"])
	        ->setTo($clientMail->getEmail())
	        ->setBody(
	            $this->renderView(
	                'contact/email/visitor.html.twig',
	                array('mail' => $clientMail)
	            ),
	            'text/html'
	        );

	       	// message a envoyer au service contacté
	       	$from_name = $clientMail->getFirstname()." ".$clientMail->getLastname();
	       	
	        $message2 = (new \Swift_Message($clientMail->getSubject()))
	        ->setFrom([$clientMail->getEmail()=>$from_name])
	        ->setTo($department->getEmail())
            ->addCc($adminInfo["email"])
	        ->setBody(
	            $this->renderView(
	                'contact/email/webmaster.html.twig',
	                array('mail' => $clientMail)
	            ),
	            'text/html'
	        );

            try {
                $mailer->send($message);
                $mailer->send($message2);
            } catch (\Exception $e) {
                
            }
	    	
	    	$em = $this->getDoctrine()->getManager();

	    	if(!$department->getId()){
	    		$rep = $em->getRepository(Department::class);
	    		$clientMail->setDepartment($rep->findOneBySlug('communication'));
	    	}

	    	$em->persist($clientMail);
	    	$em->flush();

    		$this->addFlash("notice-success",1);
    		return $this->redirectToRoute("contact_index");
    	}

        return $this->render('contact/index.html.twig',array(
        	"form"=>$form->createView(),
        ));
    }

    /**
    * @Route("/email-visitor", name="contact_email_visitor")
    */
    public function emailVisitorAction(Request $request){
    	return $this->render('contact/email/visitor.html.twig',[
    		"mail"=>array(
    			"firstname"=>"Zacharie Aké",
    			"lastname"=>"Assagou",
    		)
    	]);
    }

    /**
    * @Route("/email-webmaster", name="contact_email_webmaster")
    */
    public function emailWebmasterAction(Request $request){
    	return $this->render('contact/email/webmaster.html.twig',[
    		"mail"=>array(
    			"firstname"=>"Zacharie Aké",
    			"lastname"=>"Assagou",
    			"subject"=>"Demande de rendez-vous",
    			"message"=>"Veuillez trouver là mes salutations et consideration de haut niveau. je viens vous rencontrer dans le cadre de l'organisation de la quatrieme edition de la gouapalistant et souhaitant vous",
    			"email"=>"zakeszako@ogoeuu.uk",
    			"department"=>array(
    				"name"=>"Communication"
    			)
    		)
    	]);
    }

}
