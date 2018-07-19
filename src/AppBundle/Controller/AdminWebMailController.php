<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\WebsiteMail;
/**
* @Route("/admin/webmail", name="admin_webmail_")
*/
class AdminWebMailController extends Controller
{
	/**
    * @Route("/{folder}/{message_id}", requirements={"message_id":"(\d+)?","folder":"inbox|sent"}, defaults={"folder":"inbox"}, name="index")
    */
    public function indexAction(Request $request,$folder,$message_id=null){
    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(WebsiteMail::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($message_id){
            $request->query->set('id',intval($message_id));
        }
        $params = $request->query->all();
        $messages = $rep->search($params,$limit,$offset);

    	return $this->render('admin/inbox/index.html.twig',array(
    		"messages"=>$messages,
    	));
    }

}
