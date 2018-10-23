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

    	$em = $this->getDoctrine()->getManager();
    	$rep = $em->getRepository(Movie::class);

        $limit = intval($request->query->get('limit',20));
        $offset = intval($request->query->get('offset',0));

        $limit = $limit > 20 ? 20 : $limit;
        $offset = $offset < 0 ? 0 : $offset;

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

            if(($url = $programme->getTrailer())){
                $links[] = $url;
            }

            if($this->isGranted('IS_AUTHENTICATED_FULLY')){
                if(($url = $programme->getEpisode1())){
                    $links[] = $url;
                }

                if(($url = $programme->getEpisode2())){
                    $links[] = $url;
                }

                if(($url = $programme->getEpisode3())){
                    $links[] = $url;
                }
            }

            $links = array_filter($links,function($el){
                return preg_match("#/coteouestv/#", $el)?false:true;
            });



            $requestVimeo2 = function (array $videos,callable $fn = null)use(&$lib,&$token){
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

                                if($el == $el2['link'] || @$code[1] == @$el2['code']){
                                    $data[] = $el2;
                                    break;
                                }
                            }
                        }
                        
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


                            $label = "";
                            switch ($i) {
                                case 0:
                                    $label = 'trailer';
                                break;
                                
                                default:
                                    $label = 'lien '.$i;
                                break;
                            }

                            $vimeoRsrc[$label] = $el;
                        }
                        
                    });
                } catch (\Exception $e) {
                    
                }
            }
            
    		return $this->render('movie/movie-single.html.twig',array(
                "programme"=>$programme,
                "vimeoRsrc"=>$vimeoRsrc,
                "otherMovies"=>$otherMovies,
    		));
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
    	$rep = $em->getRepository(\AppBundle\Entity\CatalogSectionCategory::class);
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

}
