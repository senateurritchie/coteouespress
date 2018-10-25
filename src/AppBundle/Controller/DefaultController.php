<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Entity\Actor;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Director;

class DefaultController extends Controller{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $rep_movie = $em->getRepository(Movie::class);
       
        //  on charge les programmes
        $ev = array(
            //"isPublished"=>1,
            "inTheather"=>1,
        );
        $programmes = $rep_movie->findBy($ev,["id"=>"desc"],9);

        //  on charge les acteurs
        $rep = $em->getRepository(Actor::class);
        $actors = $rep->findBy([],["id"=>"desc"],6);

        //  on charge les realisateurs
        $rep = $em->getRepository(Director::class);
        $directors = $rep->findBy(["inTheather"=>1],["id"=>"desc"],6);

        //  on charge les producteurs
        $director_exclusivity = $rep->findOneBy(["hasExclusivity"=>1],["id"=>"desc"]);

        // video à voir
        $vimeo_p = $this->getParameter('app.vimeo');
            
        $client_id = $vimeo_p['client_id'];
        $client_secret = $vimeo_p['client_secret'];
        $token = $vimeo_p['access_token'];
        $vimeoRsrc = array();

        $lib = new \Vimeo\Vimeo($client_id, $client_secret);
        // use the token
        $lib->setToken($token);
        
        $requestVimeo2 = function (callable $fn = null)use(&$lib){
            $endpoint = '/me/videos';

            if(($response = $lib->request($endpoint, ["query"=>"Trailer TRAILER trailer","direction"=>"desc","page"=>1,"per_page"=>4,"weak_search"=>true], 'GET'))){

                if($response['status'] == 200){
                    $e = array_map(function($el){
                        $code = array_slice(explode("/", $el['uri']),-1)[0];
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
                            "thumbnail"=>preg_replace("#(\d+x\d+)#", "350x197",$el['pictures']["sizes"][0]['link']),
                        );

                    },$response['body']['data']);

                    if($fn){
                        $ret = call_user_func($fn,$e);
                    }
                }
            }
        };

        $vimeo_cache_path = $this->getParameter('kernel.project_dir')."/var/cache/index_vimeo_cache.json";

        // on lit directement dans le fichier
        if(file_exists($vimeo_cache_path) && is_file($vimeo_cache_path)){
            $el = json_decode(file_get_contents($vimeo_cache_path),true);

            $elapsedTime = time() - $el["timestamp"];
            $elapsedDays =  ((($elapsedTime / 60)/60)/24);

            // 7 jour pour le cache
            if($elapsedDays <= 7){
                $vimeoRsrc = $el['data'];
                goto vimeo_cache_skip;
            }
        }
        
        try {
            $requestVimeo2(function($data)use(&$vimeoRsrc){
                foreach ($data as $i => $el) {
                    $vimeoRsrc[] = $el;
                }

                if(count($vimeoRsrc)){
                    // on enregistre dans un fichier
                    if(!file_exists($vimeo_cache_path)){
                        file_put_contents($vimeo_cache_path, json_encode([
                            "timestamp"=>time(),
                            "data"=>$vimeoRsrc
                        ]));
                    }
                }
            });
        } catch (\Exception $e) {
            
        }
        
        vimeo_cache_skip:


        return $this->render('default/index.html.twig',array(
            "programmes"=>$programmes,
            "inTheather"=>$rep_movie->findOneBy(['hasExclusivity'=>1,"isPublished"=>1]),
            "actors"=>$actors,
            "directors"=>$directors,
            "director_exclusivity"=>$director_exclusivity,
            "vimeoRsrc"=>$vimeoRsrc,
        ));
    }


    /**
     * @Route("/a-propos-de-nous/", name="presentation")
     */
    public function presentationAction(Request $request){
        return $this->render('default/l-entreprise.html.twig');
    }

    /**
    * @Route("/switch-language/{lang_dest}/", requirements={"lang_dest":"fr|en"}, name="switch_lang")
    */
    public function switchLanguageAction(Request $request,$lang_dest){
        $cookie = new Cookie("_locale",$lang_dest);
        $response = $this->redirect($request->server->get("HTTP_REFERER"));
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
    * @Route("/conditions-generales-d-utilisation", name="cgu")
    */
    public function cguAction(){
        return $this->render('default/cgu.html.twig');
    }

    /**
    * @Route("/politique-de-confidentialite", name="privacy_policy")
    */
    public function privacyPolicyAction(){
        return $this->render('default/privacy-policy.html.twig');
    }

    public function renderFooter(){
        $em = $this->getDoctrine()->getManager();

        //  on charge les categories
        $rep = $em->getRepository(Category::class);
        $categories = $rep->findBy([],["name"=>"asc"]);

        return $this->render('footer.html.twig',array(
            "categories"=>$categories,
        ));
    }
}
