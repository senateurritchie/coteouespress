<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\AcceptHeader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Movie;
use AppBundle\Form\MovieType;

use AppBundle\Entity\MovieGenre;
use AppBundle\Entity\MovieLanguage;
use AppBundle\Entity\MovieCountry;
use AppBundle\Entity\MovieActor;
use AppBundle\Entity\MovieProducer;
use AppBundle\Entity\MovieDirector;
use AppBundle\Entity\MovieScene;

use AppBundle\Form\CatalogAdminSearchType;
use AppBundle\Entity\Catalog;
use AppBundle\Entity\Metadata;
use AppBundle\Form\MetadataType;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
* @Route("/admin/movies", name="admin_movie_")
*/
class AdminMovieController extends Controller
{
	/**
    * @Route("/{movie_id}", requirements={"movie_id":"(\d+)?"}, name="index")
    */
    public function indexAction(Request $request,$movie_id=null){

        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Movie::class);
        $date = new \Datetime();

    	$limit = intval($request->query->get('limit',20));
    	$offset = intval($request->query->get('offset',0));

    	$limit = $limit > 20 ? 20 : $limit;
    	$offset = $offset < 0 ? 0 : $offset;

        if($movie_id){
            $request->query->set('id',intval($movie_id));
        }

        $params = $request->query->all();
        $params['order_id'] = "DESC";

        $data = $rep->search($params,$limit,$offset);

        // requete ajax
        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            
            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("Element introuvable");
                }
                $data = $data[0];
            }


            $result = array();
            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);
            $result['model'] = $json;
            $result['view'] = "";
            $view;

            if(is_array($data)){
                $view = $this->render('admin/movie/item-render.html.twig',array(
                    "data"=>$data,
                ));
            }
            else{

                $form2 = $this->createForm(MovieType::class,$data,[
                    'upload_dir' => $this->getParameter('public_upload_directory'),
                    "action"=>$this->generateUrl("admin_movie_update",["movie_id"=>$data->getId()])
                ]);

                $em->refresh($data);

                $view = $this->render('admin/movie/selected-view.html.twig',array(
                    "data"=>$data,
                    "form"=>$form2->createView(),
                ));
            }

            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                return $view;
            }
            
            $result['view'] = $view->getContent();

            $json = json_encode($result);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


        // soumissio de formulaire
    	$item = new Movie();
    	$form = $this->createForm(MovieType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){

            $em->persist($item);

            // gestion des genres
            $genres = $form->get('genres')->getData();
            $db = [];
            foreach ($genres as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieGenre();
                $e->setMovie($item);
                $e->setGenre($el);
                $e->setCreateAt($date);
                $db[] = $el->getId();
                $em->persist($e);
            }

            // gestion des langues disponible
            $languages = $form->get('languages')->getData();
            $db = [];
            foreach ($languages as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieLanguage();
                $e->setMovie($item);
                $e->setLanguage($el);
                $e->setCreateAt($date);
                $em->persist($e);
            }

            // gestion des pays
            $countries = $form->get('countries')->getData();
            $db = [];
            foreach ($countries as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieCountry();
                $e->setMovie($item);
                $e->setCountry($el);
                $e->setCreateAt($date);
                $em->persist($e);
            }

            // gestion des acteurs
            $actors = $form->get('actors')->getData();
            $db = [];
            foreach ($actors as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieActor();
                $e->setMovie($item);
                $e->setActor($el);
                $e->setCreateAt($date);
                $em->persist($e);
            }

            // gestion des producers
            $producers = $form->get('producers')->getData();
            $db = [];
            foreach ($producers as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieProducer();
                $e->setMovie($item);
                $e->setProducer($el);
                $e->setCreateAt($date);
                $em->persist($e);
            }

            // gestion des directors
            $directors = $form->get('directors')->getData();
            $db = [];
            foreach ($directors as $key => $el) {
                if(in_array($el->getId(), $db)) continue;

                $e = new MovieDirector();
                $e->setMovie($item);
                $e->setDirector($el);
                $e->setCreateAt($date);
                $em->persist($e);
            }

    		$em->flush();
    		$this->addFlash('notice-success',"Votre programme a été ajouté avec succes");
    		return $this->redirectToRoute("admin_movie_index");
    	}
        else{
            foreach ($form->all() as $child) {
                if (!$child->isValid() && count($child->getErrors())) {
                    $formatted = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                    $this->addFlash('notice-error',$formatted);
                }
            }
        }
        
        $catalogue = new Catalog();
        $form_search = $this->createForm(CatalogAdminSearchType::class,$catalogue);

        $metadata = new Metadata();
        $form_metadata = $this->createForm(MetadataType::class,$metadata,[
            'upload_dir' => $this->getParameter('private_upload_directory'),
        ]);

    	return $this->render('admin/movie/index.html.twig',array(
    		"data"=>$data,
            "form"=>$form->createView(),
            "form_search"=>$form_search->createView(),
            "form_metadata"=>$form_metadata->createView(),
    	));
    }

    /**
    * @Route("/{movie_id}/update", requirements={"movie_id":"\d+"}, name="update")
    * @Method("POST")
    */
    public function updateAction(Request $request,$movie_id){
        // protection par role
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException();
        }

        $cloned = clone($item);

        $oldCoverImg = $item->getCoverImg();
        $oldLandscapeImg = $item->getLandscapeImg();
        $oldPortraitImg = $item->getPortraitImg();

        $form = $this->createForm(MovieType::class,$item,[
            'upload_dir' => $this->getParameter('public_upload_directory'),
        ]);

        $form->handleRequest($request);
        
        foreach ($form->all() as $child) {
            if (!$child->isValid() && count($child->getErrors())) {
                $formatted = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                $this->addFlash('notice-error',$formatted);
            }
        }


        if($form->isSubmitted() && $form->isValid()){
            $date = new \Datetime();
            $em->merge($item);

            if(!$item->getCoverImg() && $oldCoverImg){
                $item->setCoverImg($oldCoverImg);
            }

            if(!$item->getLandscapeImg() && $oldLandscapeImg){
                $item->setLandscapeImg($oldLandscapeImg);
            }

            if(!$item->getPortraitImg() && $oldPortraitImg){
                $item->setPortraitImg($oldPortraitImg);
            }

            if($cloned->getCategory()->getId() != $item->getCategory()->getId()){
                $el = $cloned->getCategory();
                $nbr = intval($el->getMovieNbr())-1;
                $el->setMovieNbr($nbr);

                $el = $item->getCategory();
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }

            
            // gestion des genres
            $genres = $form->get('genres')->getData();
            if(count($genres)){
                $db = [];

                $rep = $em->getRepository(MovieGenre::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getGenre()->getId();
                    }, $old);
                }
                foreach ($genres as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieGenre();
                    $e->setMovie($item);
                    $e->setGenre($el);
                    $e->setCreateAt($date);
                    $db[] = $el->getId();
                    $em->persist($e);
                }
            }

            // gestion des langues disponible
            $languages = $form->get('languages')->getData();
            if(count($languages)){
                $db = [];

                $rep = $em->getRepository(MovieLanguage::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getLanguage()->getId();
                    }, $old);
                }
                foreach ($languages as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieLanguage();
                    $e->setMovie($item);
                    $e->setLanguage($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                }
            }

            // gestion des pays
            $countries = $form->get('countries')->getData();
            if(count($countries)){
                $db = [];
                $rep = $em->getRepository(MovieCountry::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getCountry()->getId();
                    }, $old);
                }
                foreach ($countries as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieCountry();
                    $e->setMovie($item);
                    $e->setCountry($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                }
            }

            // gestion des acteurs
            $actors = $form->get('actors')->getData();
            if(count($actors)){
                $db = [];

                $rep = $em->getRepository(MovieActor::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getActor()->getId();
                    }, $old);
                }
                foreach ($actors as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieActor();
                    $e->setMovie($item);
                    $e->setActor($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                }
            }

            // gestion des producers
            $producers = $form->get('producers')->getData();
            if(count($producers)){
                $db = [];

                $rep = $em->getRepository(MovieProducer::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getProducer()->getId();
                    }, $old);
                }

                foreach ($producers as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieProducer();
                    $e->setMovie($item);
                    $e->setProducer($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                }
            }

            // gestion des directors
            $directors = $form->get('directors')->getData();
            if(count($directors)){
                $db = [];

                $rep = $em->getRepository(MovieDirector::class);
                if(($old = $rep->findBy(["movie"=>$item]))){
                    $db = array_map(function($el){
                        return $el->getDirector()->getId();
                    }, $old);
                }

                foreach ($directors as $key => $el) {
                    if(in_array($el->getId(), $db)) continue;

                    $e = new MovieDirector();
                    $e->setMovie($item);
                    $e->setDirector($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                }
            }


            $em->flush();

            if($oldCoverImg && $item->getCoverImg() && $item->getCoverImg() != $oldCoverImg){
                $path = $this->getParameter('public_upload_directory').'/'.$oldCoverImg;
                unlink($path);
            }

            if($oldCoverImg && $item->getLandscapeImg() && $item->getLandscapeImg() != $oldLandscapeImg){
                $path = $this->getParameter('public_upload_directory').'/'.$oldLandscapeImg;
                unlink($path);
            }

            if($oldCoverImg && $item->getPortraitImg() && $item->getPortraitImg() != $oldPortraitImg){
                $path = $this->getParameter('public_upload_directory').'/'.$oldPortraitImg;
                unlink($path);
            }


            $this->addFlash('notice-success',"Votre programme a été mise à jour avec succes");
            return $this->redirectToRoute('admin_movie_index');
        }

        return $this->redirectToRoute('admin_movie_index',["movie_id"=>$item->getId(),"modal"=>1]);
    }

    /**
    * @Route("/{movie_id}/delete", requirements={"movie_id":"\d+"}, name="delete")
    * @Method("POST")
    */
    public function deleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException();
        }

        $em->remove($item);
        $em->flush();
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);

        return $this->json($result);
    }


    /**
    * @Route("/{movie_id}/image/upload", requirements={"movie_id":"\d+"}, name="upload")
    * @Method("POST")
    */
    public function imageUploadAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException();
        }

        $_target = $request->request->get('_target');
        $oldName = null;
        
        switch($_target){
        
            case "portrait":
                $oldName = trim($item->getPortraitImg());
            break;

            case "landscape":
                $oldName = trim($item->getLandscapeImga());
            break;

            case "cover":
                $oldName = trim($item->getCoverImg());
            break;

            default:
                goto upload_end;
            break;
        }


        $form = $this->createForm(MovieType::class,$item,array(
            //'csrf_protection' => false,
            'upload_dir' => $this->getParameter('public_upload_directory'),
            "use_for"=>"upload",
        ));
        //$form->submit($request->files->all());
        $form->handleRequest($request);

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $em->merge($item);
            $em->flush();

            switch($_target){
        
                case "portrait":
                    if($oldName && $oldName != $item->getPortraitImg()){
                        $path = $this->getParameter('public_upload_directory').'/'.$oldName;
                        unlink($path);
                    }
                break;

                case "landscape":
                    if($oldName && $oldName != $item->getLandscapeImga()){
                        $path = $this->getParameter('public_upload_directory').'/'.$oldName;
                        unlink($path);
                    }
                break;

                case "cover":
                    if($oldName && $oldName != $item->getCoverImg()){
                        $path = $this->getParameter('public_upload_directory').'/'.$oldName;
                        unlink($path);
                    }
                break;
            }

            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";
            $result["data"] = json_decode($this->get("serializer")->serialize($item,'json',array("groups"=>["group1"])),true);
        }

        upload_end:

        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/gallery/upload", requirements={"movie_id":"\d+"}, name="upload")
    * @Method("POST")
    */
    public function galleryUploadAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(MovieType::class,$item,array(
            'csrf_protection' => false,
            'upload_dir' => $this->getParameter('public_upload_directory'),
            "use_for"=>"upload_gallery",
        ));
        $form->handleRequest($request);
        //$form->submit($request->files->all(),false);


        foreach ($form->all() as $child) {

            if (!$child->isValid()) {
                $result['errors'][] = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
            }
        }

        if($form->isSubmitted() && $form->get('gallery')->isValid()){

            $item->preUpdate();

            $galleries = $form->get("gallery")->getData();

            if(count($galleries)){
                $items;
                $date = new \Datetime();

                foreach ($galleries as $key => $el) {
                    $e = new MovieScene();
                    $e->setMovie($item);
                    $e->setImage($el);
                    $e->setCreateAt($date);
                    $em->persist($e);
                    $items = $e;
                }

                $result['status'] = true;
                $result['message'] = "modification effectuée avec succès";

                $em->flush();

                $result["data"] = json_decode($this->get("serializer")->serialize($items,'json',array("groups"=>["group1"])),true);

            }
        }

        return $this->json($result);
    }


    /**
    * @Route("/{movie_id}/gallery/delete", requirements={"movie_id":"\d+"}, name="gallery_delete")
    * @Method("POST")
    */
    public function galleryDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_gallery = $em->getRepository(MovieScene::class);
        $result = ["status"=>false];
        $scene_id = intval($request->request->get('scene_id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($scene = $rep_gallery->findOneBy(["movie"=>$item,"id"=>$scene_id]))){
            throw $this->createNotFoundException("Photo de gallerie introuvable");
        }

        $em->remove($scene);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/language/delete", requirements={"movie_id":"\d+"}, name="language_delete")
    * @Method("POST")
    */
    public function languageDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieLanguage::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Langue introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/actor/delete", requirements={"movie_id":"\d+"}, name="actor_delete")
    * @Method("POST")
    */
    public function actorDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieActor::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Acteur introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/genre/delete", requirements={"movie_id":"\d+"}, name="genre_delete")
    * @Method("POST")
    */
    public function genreDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieGenre::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Genre introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/producer/delete", requirements={"movie_id":"\d+"}, name="producer_delete")
    * @Method("POST")
    */
    public function producerDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieProducer::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Producteur introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/country/delete", requirements={"movie_id":"\d+"}, name="country_delete")
    * @Method("POST")
    */
    public function countryDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieCountry::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Pays introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/director/delete", requirements={"movie_id":"\d+"}, name="director_delete")
    * @Method("POST")
    */
    public function directorDeleteAction(Request $request,$movie_id){

        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $rep_2 = $em->getRepository(MovieDirector::class);
        $result = ["status"=>false];
        $id = intval($request->request->get('id'));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        if(!($target = $rep_2->findOneBy(["movie"=>$item,"id"=>$id]))){
            throw $this->createNotFoundException("Réalisateur introuvable");
        }

        $em->remove($target);
        $result['status'] = true;
        $result['message'] = "modification effectuée avec succès";
        $em->flush();
    
        return $this->json($result);
    }


    /**
    * @Route("/{movie_id}/translate", requirements={"movie_id":"\d+"}, name="translate")
    * @Method("POST")
    */
    public function translateAction(Request $request,$movie_id){

        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_CATALOG_TRANSLATE'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];
        

        $locale = strip_tags(trim($request->request->get('locale')));
        $tagline = strip_tags(trim($request->request->get('tagline')));
        $logline = strip_tags(trim($request->request->get('logline')));
        $synopsis = strip_tags(trim($request->request->get('synopsis')));

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $isValid = false;
       
        if($locale){
            if($tagline){
                $repository->translate($item, 'tagline', $locale,$tagline);
                $isValid = true;
            }

            if($logline){
                $repository->translate($item, 'logline', $locale,$logline);
                $isValid = true;
            }

            if($synopsis){
                $repository->translate($item, 'synopsis', $locale,$synopsis);
                $isValid = true;
            }

            $result['status'] = true;
            $result['message'] = "modification effectuée avec succès";

            if($isValid){
                $em->flush();
            }
        }
        else{
            $result['message'] = "veuillez saisir la langue.";
        }

        
    
        return $this->json($result);
    }

    /**
    * @Route("/{movie_id}/translations", requirements={"movie_id":"\d+"}, name="translations")
    * @Method("GET")
    */
    public function translationsAction(Request $request,$movie_id){

        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_CATALOG_TRANSLATE'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous ne pouvez pas éffectuer cette action");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];
        

        if(!($item = $rep->find($movie_id))){
            throw $this->createNotFoundException("Ce programme n'existe pas");
        }

        $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $translations = $repository->findTranslations($item);
        
        $result['data'] = $translations;
        $result['status'] = true;
         
        return $this->json($result);
    }


    /**
    * @Route("/metadata/upload/{model}", name="metadata_upload", requirements={"model":"webmaster|catalog"}, defaults={"model":"webmaster"} )
    * @Method({"POST"})
    */
    public function metadataUploadAction(Request $request,$model = "webmaster"){
        // protection par role
        $this->denyAccessUnlessGranted('ROLE_CATALOG_INSERT', null, 'Vous ne pouvez pas éffectuer cette action');

        $em = $this->getDoctrine()->getManager();
        $metadata = new Metadata();
        $result = ["status"=>false];

        $upload_dir = $this->getParameter('private_upload_directory');

        $form = $this->createForm(MetadataType::class,$metadata,[
            'upload_dir' => $upload_dir,
        ]);

        $form->handleRequest($request);

        foreach ($form->all() as $child) {
            if (!$child->isValid() && count($child->getErrors())) {
                $formatted = '['.$child->getName().']: '.$child->getErrors()[0]->getMessage();
                $result['errors'][] = $formatted;
                //$this->addFlash('notice-error',$formatted);
            }
        }

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($metadata);
            $em->flush();
            $result['file_download_ok'] = true;
            //$this->addFlash('notice-success',"opération éffectuée avec succès");
        }
       

        $zip_path = $upload_dir.'/'.$metadata->getFile();
        $translator = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $reader = new \AppBundle\Utils\Metadata\WebmasterMetadata($zip_path,array(
            "entity_manager"=>$em,
            "translator"=>$translator
        ));

        ob_clean();
        ob_start();

        echo "<div class='table-responsive'>";
        echo "<table cellspacing='0' class='table table-hover table-striped table-bordered'>";
        echo "<caption><h3>Aaz Metadata Reader</h3></caption>";
        $reader
        ->on("header",function($event){

            echo "<thead><tr>";
            foreach ($event->getValue() as $el) {
                echo "<th scope='col'> ".$el->getFiltered()." </th>";
            }
            echo "</tr></thead><tbody>";
        })
        ->on("sheetnames",function($event){
            
        })
        ->on("worksheetData",function($event){

        })
        ->on("data",function($event){

            echo "<tr style='border-top:1px solid #ff0000;'>";
            foreach ($event->getValue() as $el) {
                //style='text-align:center;border:1px solid #333;padding:10px'
                if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataResourceEntry){
                    echo "<td valign='top'>";

                    $resources = $el->getResources();
                    foreach ($resources as $i=>$resource) {
                        list($im,$filename) = $resource;
                        echo "<img style='margin:0px 5px 5px 0px;' alt='$filename' width='100' height='50'  src='data:image/jpeg;base64,".base64_encode($im)."'>";

                        if($i && $i % 2 == 0) echo "<br>";
                    }
                   
                    echo "</td>";
                }
                else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataEntityEntry){
                    echo "<td valign='top'><ol>";

                    $choices = $el->getChoices();

                    foreach ($choices as $i=>$choice) {
                        echo "<li><a style='text-decoration:none' href='#'>".$choice->getName()."</a></li>";
                    }
                   
                    echo "</ol></td>";
                }
                else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataChoiceEntry){
                    echo "<td valign='top'><ol>";

                    $choices = $el->getChoices();

                    foreach ($choices as $i=>$choice) {
                        echo "<li>".$choice."</li>";
                    }
                   
                    echo "</ol></td>";
                }
                else{
                    echo "<td valign='top'>".$el->getFiltered()."</td>";
                }
            }
            echo "</tr>";
        })
        ->on("end",function($event)use(&$result){
            $result['status'] = true;
        });

        
        try {
            $reader->process();
        } catch (\Exception $e) {
            $em->remove($metadata);
            $em->flush();

            ob_clean();
            $result['message'] = $e->getMessage();

            return $this->json($result);
        }
        echo " </tbody></table></div>";

        $content = ob_end_clean();

        return new Response($content,201);
    }
}
