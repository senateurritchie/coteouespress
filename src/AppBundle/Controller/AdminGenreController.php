<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Genre;
use AppBundle\Form\GenreType;

/**
* @Route("/admin/genres", name="admin_genre_")
*/
class AdminGenreController extends Controller
{
	/**
    * @Route("/{genre_id}", requirements={"genre_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$genre_id=null){

        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Genre::class);

    	$limit = intval($request->query->get('limit',50));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 50 ? 50 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($genre_id){
            $request->query->set('id',intval($genre_id));
        }
        $params = $request->query->all();
        $genres = $rep->search($params,$limit,$offset);

    	$genre = new Genre();
    	$form = $this->createForm(GenreType::class,$genre);

    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$em->persist($genre);
    		$em->flush();
    		$this->addFlash('notice-success',1);
    		return $this->redirectToRoute("admin_genre_index");
    	}

        if($request->isXmlHttpRequest()){
            if(intval(@$params['id'])){
                if(empty($genres)){
                    throw $this->createNotFoundException("Departement introuvable");
                }
                $genres = $genres[0];
            }

            $json = $this->get("serializer")->serialize($genres,'json',array('groups' => array('group1')));
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    	return $this->render('admin/genre/index.html.twig',array(
    		"genres"=>$genres,
    		"form"=>$form->createView()
    	));
    }

    /**
    * @Route("/update/{genre_id}", requirements={"genre_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$genre_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }


        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Genre::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($genre_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(GenreType::class,$item,array('csrf_protection' => false));
        $form->submit($request->request->all());

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $em->merge($item);
            $em->flush();
            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);
        }
        
        return $this->json($result);
    }

    /**
    * @Route("/delete/{genre_id}", requirements={"genre_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$genre_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Genre::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($genre_id))){
            throw $this->createNotFoundException();
        }

        $em->remove($item);
        $em->flush();
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);

        return $this->json($result);
    }
}
