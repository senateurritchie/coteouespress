<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\AcceptHeader;

use AppBundle\Form\CatalogType;
use AppBundle\Entity\Catalog;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Actor;

/**
* @Route("/programmes", name="movie_")
*/
class MovieController extends Controller{
    /**
    * @Route("/{slug}", name="index", requirements={"slug":"([\w-]+)?"})
    */
    public function indexAction(Request $request,$slug=null){

        $start_t = microtime(true);

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Movie::class);

        $limit = intval($request->query->get('limit',20));
        $offset = intval($request->query->get('offset',0));

        $limit = $limit > 20 ? 20 : $limit;
        $offset = $offset < 0 ? 0 : $offset;

       /* $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($this->getUser(),"demo");
        var_dump($encoded);*/

                    

    	if($slug){
    		if(!($programme = $rep->findOneBy(array("slug"=>$slug)))){
    			throw $this->createNotFoundException("Le programme recherché est introuvable");
    		}


            $category = $programme->getCategory();
            $otherMovies = $rep->findBy(array("category"=>$category),['id'=>"DESC"],12);

            $vimeo_p = $this->getParameter('app.vimeo');
            
            $client_id = $vimeo_p['client_id'];
            $client_secret = $vimeo_p['client_secret'];
            $token = $vimeo_p['access_token'];
            $vimeoRsrc = array();
                
            $lib = new \Vimeo\Vimeo($client_id, $client_secret);
            //$token = $lib->clientCredentials(["public","video_files"]);
            //var_dump($token['body']['access_token']);
            // accepted scopes
            //var_dump($token['body']['scope']);*/

            // use the token
            $lib->setToken($token);
            
            $links = [];
            $vimeo_cache_path = $this->getParameter('kernel.project_dir')."/var/cache/vimeo_cache_$slug.json";

            $cachedData = [];

            // on lit directement dans le fichier cache
            if(file_exists($vimeo_cache_path) && is_file($vimeo_cache_path)){
                $cachedData = json_decode(file_get_contents($vimeo_cache_path),true);

                $elapsedTime = time() - $cachedData["timestamp"];
                $elapsedMonths =  (((($elapsedTime / 60)/60)/24)/30);

                // 3 mois pour le cache
                /*if($elapsedMonths <= 3){
                    $vimeoRsrc = $el['data'];
                    goto vimeo_cache_skip;
                }*/
            }

            if(($url = $programme->getTrailer())){

                if(!isset($cachedData["data"]) || !isset($cachedData["data"][$url])){
                    $links[] = $url;
                }
                else{
                    $vimeoRsrc[] = $cachedData["data"][$url];
                }
            }

            if(1){
            //if($this->isGranted('IS_AUTHENTICATED_FULLY')){
                if(($url = $programme->getEpisode1())){
                    if(!isset($cachedData["data"])) {
                        $links[] = $url;
                    }
                    else{
                        if(isset($cachedData["data"][$url])){
                            $vimeoRsrc[] = $cachedData["data"][$url];
                        }
                        else{
                            $links[] = $url;
                        }
                    }
                }

                if(($url = $programme->getEpisode2())){
                    if(!isset($cachedData["data"])) {
                        $links[] = $url;
                    }
                    else{
                        if(isset($cachedData["data"][$url])) {
                            $vimeoRsrc[] = $cachedData["data"][$url];
                        }
                        else{
                            $vimeoRsrc = [];
                            $links[] = $url;
                        }
                    }
                }

                if(($url = $programme->getEpisode3())){
                    if(!isset($cachedData["data"])) {
                        $links[] = $url;
                    }
                    else{
                        if(isset($cachedData["data"][$url])){
                            $vimeoRsrc[] = $cachedData["data"][$url];
                        }
                        else{
                            $vimeoRsrc = [];
                            $links[] = $url;
                        }
                    }
                }
            }

            if(!empty($vimeoRsrc)){
                goto vimeo_cache_skip;
            }

            

            $links = array_filter($links,function($el){
                return preg_match("#/coteouestv/#", $el)?false:true;
            });


            $requestVimeo2 = function (array $videos,callable $fn = null)use(&$lib,&$token,&$cachedData,&$vimeo_cache_path){
                $endpoint = '/videos';

                $links = implode(",", $videos);

                if(($response = $lib->request($endpoint, ["links"=>$links,"query"=>"","weak_search"=>true], 'GET'))){

                    if($response['status'] == 200){

                        $e = array_map(function($el){
                            $code = array_slice(explode("/", $el['uri']),-1)[0];


                            $el['download'] = array_map(function($e){
                                $size = $e["size"]/1024;
                                $size /= 1024;
                                $e['size'] = round($size,2);
                                return $e;
                            }, $el['download']);


                            return array(
                                "code"=>$code,
                                "name"=>$el['name'],
                                "uri"=>$el['uri'],
                                "description"=>$el['description'],
                                "description"=>$el['description'],
                                "link"=>$el['link'],
                                "duration"=>$el['duration'],
                                "width"=>$el['width'],
                                "height"=>$el['height'],
                                "created_time"=>$el['created_time'],
                                "modified_time"=>$el['modified_time'],
                                "content_rating"=>$el['content_rating'],
                                "license"=>$el['license'],
                                "privacy"=>$el['privacy'],
                                "cover"=>preg_replace("#(\d+x\d+)#", "1920x1080",$el['pictures']["sizes"][0]['link']),
                                "thumbnail"=>$el['pictures']["sizes"][0]['link'],
                                "download"=>$el['download'],
                            );

                        },$response['body']['data']);

                        $data = [];

                        foreach ($videos as $el) {
                            foreach ($e as $key => $el2) {

                                // pour contourner les liens personalisés
                                // il faut se referer au code vimeo de la video plutot que le link


                                preg_match("#https:\/\/vimeo.com/(.+)/.+#", $el,$code);
                                if(!$code){
                                    preg_match("#https:\/\/vimeo.com/(.+)$#", $el,$code);
                                }

                                if($el == $el2['link'] || @$code[1] == @$el2['code']){
                                    $data[] = $el2;
                                    $cachedData["data"][$el] = $el2;
                                    break;
                                }
                            }
                        }
                        
                        // on enregistre dans un fichier cache
                        $cachedData["timestamp"] = time();
                        file_put_contents($vimeo_cache_path, json_encode($cachedData));

                        if($fn){
                            $ret = call_user_func($fn,$data);
                        }
                    }
                }
            };


            if(count($links)){

                try {
                    $requestVimeo2($links,function($data)use(&$vimeoRsrc){
                        foreach ($data as $i => $el) {
                            $vimeoRsrc[] = $el;
                        }
                        
                    });
                } catch (\Exception $e) {
                                

                }
            }

            vimeo_cache_skip:
            
    		return $this->render('movie/movie-single.html.twig',array(
                "programme"=>$programme,
                "vimeoRsrc"=>$vimeoRsrc,
                "otherMovies"=>$otherMovies,
    		)); // 
    	}


    	$catalogue = new Catalog();
    	$form = $this->createForm(CatalogType::class,$catalogue,["use_for_mode"=>"program_page"]);
    	$params = $request->query->all();

        $params["locale"] = $request->getLocale();
        //$params["published"] = "yes";

    	$data = $rep->search($params,$limit,$offset);

        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                $charset = $item->getAttribute('charset', 'utf-8');
                $quality = $item->getQuality();

                return $this->render('movie/search-movies-render.html.twig',array(
                    "programmes"=>$data,
                ));
            }

            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("programme introuvable");
                }
                $data = $data[0];
            }

            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);
        

            $json = json_encode($json);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


    	//  on charge les categories
    	$rep = $em->getRepository(\AppBundle\Entity\Category::class);
    	$categories = $rep->findBy([],["name"=>"asc"]);

    	//  on charge les genres
    	$rep = $em->getRepository(Genre::class);
        $params = [];

        
        if($request->query->get('category')){
            $params["with_program_category"] = strip_tags(trim($request->query->get('category')));
        }
    	$genres = $rep->search($params,20);

    	return $this->render('movie/search.html.twig',array(
    		"form"=>$form->createView(),
    		"programmes"=>$data,
    		"categories"=>$categories,
    		"genres"=>$genres,
    	));
    }



    /**
    * @Route("/{slug}/downloads/{download_type}", name="downloads", requirements={"slug":"[\w-]+","download_type":"vimeo|advertising"})
    */
    public function downloadListAction(Request $request,$slug,$download_type){

        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            throw $this->createNotFoundException("Veuillez vous connecter");
        }


        $start_t = microtime(true);

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);


        if(!($programme = $rep->findOneBy(array("slug"=>$slug)))){
            throw $this->createNotFoundException("Le programme recherché est introuvable");
        }

        $vimeo_p = $this->getParameter('app.vimeo');
        
        $client_id = $vimeo_p['client_id'];
        $client_secret = $vimeo_p['client_secret'];
        $token = $vimeo_p['access_token'];
        $vimeoRsrc = array();
            
        $lib = new \Vimeo\Vimeo($client_id, $client_secret);
        $lib->setToken($token);
        $links = [];
        $vimeo_cache_path = $this->getParameter('kernel.project_dir')."/var/cache/vimeo_cache_$slug.json";

        $cachedData = [];

        // on lit directement dans le fichier cache
        if(file_exists($vimeo_cache_path) && is_file($vimeo_cache_path)){
            /*$cachedData = json_decode(file_get_contents($vimeo_cache_path),true);

            $elapsedTime = time() - $cachedData["timestamp"];
            $elapsedDays =  ((($elapsedTime / 60)/60)/24);

            // 1 jour pour le cache
            if($elapsedDays >= 1){
                $cachedData = [];
            }*/
        }

        if(($url = $programme->getTrailer())){
            if(!isset($cachedData["data"]) || !isset($cachedData["data"][$url])){
                $links[] = $url;
            }
            else{
                $vimeoRsrc[] = $cachedData["data"][$url];
            }
        }
        if(($url = $programme->getEpisode1())){
            if(!isset($cachedData["data"])) {
                $links[] = $url;
            }
            else{
                if(isset($cachedData["data"][$url])){
                    $vimeoRsrc[] = $cachedData["data"][$url];
                }
                else{
                    $links[] = $url;
                }
            }
        }
        if(($url = $programme->getEpisode2())){
            if(!isset($cachedData["data"])) {
                $links[] = $url;
            }
            else{
                if(isset($cachedData["data"][$url])) {
                    $vimeoRsrc[] = $cachedData["data"][$url];
                }
                else{
                    $vimeoRsrc = [];
                    $links[] = $url;
                }
            }
        }
        if(($url = $programme->getEpisode3())){
            if(!isset($cachedData["data"])) {
                $links[] = $url;
            }
            else{
                if(isset($cachedData["data"][$url])){
                    $vimeoRsrc[] = $cachedData["data"][$url];
                }
                else{
                    $vimeoRsrc = [];
                    $links[] = $url;
                }
            }
        }
    
        if(!empty($vimeoRsrc)){
            goto vimeo_cache_skip;
        }

        $links = array_filter($links,function($el){
            return preg_match("#/coteouestv/#", $el)?false:true;
        });

        $requestVimeo2 = function (array $videos,callable $fn = null)use(&$lib,&$token,&$cachedData,&$vimeo_cache_path){
            $endpoint = '/videos';

            $links = implode(",", $videos);

            if(($response = $lib->request($endpoint, ["links"=>$links,"query"=>"","weak_search"=>true], 'GET'))){

                if($response['status'] == 200){

                    $e = array_map(function($el){
                        $code = array_slice(explode("/", $el['uri']),-1)[0];


                        $el['download'] = array_map(function($e){
                            $size = $e["size"]/1024;
                            $size /= 1024;
                            $e['size'] = round($size,2);
                            return $e;
                        }, $el['download']);


                        return array(
                            "code"=>$code,
                            "name"=>$el['name'],
                            "uri"=>$el['uri'],
                            "description"=>$el['description'],
                            "description"=>$el['description'],
                            "link"=>$el['link'],
                            "duration"=>$el['duration'],
                            "width"=>$el['width'],
                            "height"=>$el['height'],
                            "created_time"=>$el['created_time'],
                            "modified_time"=>$el['modified_time'],
                            "content_rating"=>$el['content_rating'],
                            "license"=>$el['license'],
                            "privacy"=>$el['privacy'],
                            "cover"=>preg_replace("#(\d+x\d+)#", "1920x1080",$el['pictures']["sizes"][0]['link']),
                            "thumbnail"=>$el['pictures']["sizes"][0]['link'],
                            "download"=>$el['download'],
                        );

                    },$response['body']['data']);

                    $data = [];

                    foreach ($videos as $el) {
                        foreach ($e as $key => $el2) {

                            // pour contourner les liens personalisés
                            // il faut se referer au code vimeo de la video plutot que le link

                            preg_match("#https:\/\/vimeo.com/(.+)/.+#", $el,$code);
                            if(!$code){
                                preg_match("#https:\/\/vimeo.com/(.+)$#", $el,$code);
                            }

                            if($el == $el2['link'] || @$code[1] == @$el2['code']){
                                $data[] = $el2;
                                $cachedData["data"][$el] = $el2;
                                break;
                            }
                        }
                    }
                    
                    // on enregistre dans un fichier cache
                    $cachedData["timestamp"] = time();
                    file_put_contents($vimeo_cache_path, json_encode($cachedData));

                    if($fn){
                        $ret = call_user_func($fn,$data);
                    }
                }
            }
        };

        if(count($links)){
            try {
                $requestVimeo2($links,function($data)use(&$vimeoRsrc){
                    foreach ($data as $i => $el) {
                        $vimeoRsrc[] = $el;
                    }
                    
                });
            } catch (\Exception $e) {
                            
            }
        }

        vimeo_cache_skip:
        
        return $this->render('movie/movie-single-download-list.html.twig',array(
            "vimeoRsrc"=>$vimeoRsrc,
            "programme"=>$programme,
        )); // 
    }

}
