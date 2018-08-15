<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Catalog;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
* @Route("/admin/export", name="admin_export_")
*/
class AdminExportController extends Controller
{
    /**
    * @Route("/", name="preview")
    * @Method({"GET"})
    */
    public function previewAction(Request $request){
        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);

        $params = $request->query->all();

        $limit = @$params['limit'] ? intval($params['limit']) : -1;
        $data = $rep->search($params, $limit);

        $cHeader = new \AppBundle\Utils\Metadata\HeaderValidator\CatalogHeaderValidator();
        $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $formattedData = [];
        $cFields = $cHeader->getFields();

        foreach ($data as $key => $el) {
            $item = [];

            $formatData = $el->getFormat();
            $formatDuration = null;
            $formatEps = null;

            if ($formatData){
                $e = explode("x", $formatData);
                $formatEps = $e[0];
                $formatDuration = $e[1];
            }

            $translations = $repository->findTranslations($el);
        
            $item = [
                "uniqueKey"=>\AppBundle\Entity\User::generateToken(10),
                "name"=>$el->getName(),
                "originalName"=>$el->getOriginalName(),
                "category"=>$el->getCategory()->getName(),
                "mention"=>$el->getMention(),
                "format"=>$formatData,
                "duration"=>$formatDuration,
                "episodeNbr"=>$formatEps,
                "producers"=>[],
                "genres"=>[],
                "countries"=>[],
                "directors"=>[],
                "casting"=>[],

                "yearStart"=>$el->getYearStart()?$el->getYearStart()->format('Y'):null,
                "yearEnd"=>$el->getYearEnd()?$el->getYearEnd()->format('Y'):null,

                "synopsis"=>$el->getSynopsis(),
                "tagline"=>$el->getTagline(),
                "logline"=>$el->getLogline(),

                "synopsis_en"=>@$translations["en"]["synopsis"],
                "tagline_en"=>@$translations["en"]["tagline"],
                "logline_en"=>@$translations["en"]["logline"],

                "synopsis_ar"=>@$translations["ar"]["synopsis"],
                "tagline_ar"=>@$translations["ar"]["tagline"],
                "logline_ar"=>@$translations["ar"]["logline"],

                "reward"=>$el->getReward(),
                "award"=>$el->getAward(),
                "audience"=>$el->getAudience(),
                "image"=>$el->getPortraitImg(),
                "language"=>$el->getLanguage()?$el->getLanguage()->getName():null,

                "versions"=>[],
                "trailer"=>$el->getTrailer(),
                "episode1"=>$el->getEpisode1(),
                "episode2"=>$el->getEpisode2(),
                "episode3"=>$el->getEpisode3(),
            ];

            // les produceurs
            foreach ($el->getProducers() as $e) {
                $item["producers"][] = $e->getProducer()->getName();
            }
            // les genres
            foreach ($el->getGenres() as $e) {
                $item["genres"][] = $e->getGenre()->getName();
            }
            // les countries
            foreach ($el->getCountries() as $e) {
                $item["countries"][] = $e->getCountry()->getName();
            }
            // les directors
            foreach ($el->getDirectors() as $e) {
                $item["directors"][] = $e->getDirector()->getName();
            }
            // les acteurs
            foreach ($el->getActors() as $e) {
                $item["casting"][] = $e->getActor()->getName();
            }
            // les versions
            foreach ($el->getLanguages() as $e) {
                $item["versions"][] = $e->getLanguage()->getName();
            }

            $formattedData[] = $item;
        }


        if($request->query->has('dump')){
            $entryCount = count($cFields);

            $excelData = array_map(function($item)use(&$entryCount,&$cFields){
                $itemExcel = array_fill(0,$entryCount,'-');

                // formattage des données excel
                $itemExcel[array_search("Cle_unique", $cFields)] = $item['uniqueKey'];
                $itemExcel[array_search("TitreVO", $cFields)] = $item['originalName'];
                $itemExcel[array_search("TitreExploitation", $cFields)] = $item['name'];
                $itemExcel[array_search("Categorie", $cFields)] = $item['category'];
                $itemExcel[array_search("Mention", $cFields)] = $item['mention'];
                $itemExcel[array_search("Format", $cFields)] = $item['format'];
                $itemExcel[array_search("Durée", $cFields)] = $item['duration'];
                $itemExcel[array_search("NombreEpisodes", $cFields)] = $item['episodeNbr'];
                $itemExcel[array_search("Producteur", $cFields)] = implode(", ",$item['producers']);
                $itemExcel[array_search("Genre", $cFields)] = implode(", ",$item['genres']);
                $itemExcel[array_search("OrigineProduction", $cFields)] = implode(", ",$item['countries']);
                $itemExcel[array_search("AnneeProduction", $cFields)] = $item['yearStart'].($item['yearEnd'] && $item['yearEnd'] != $item['yearStart'] ?'-'.$item['yearEnd']:'');
                $itemExcel[array_search("Realisateur", $cFields)] = implode(", ",$item['directors']);
                $itemExcel[array_search("Synopsis_fr", $cFields)] = $item['synopsis'];
                $itemExcel[array_search("tagline_fr", $cFields)] = $item['tagline'];
                $itemExcel[array_search("logline_fr", $cFields)] = $item['logline'];

                $itemExcel[array_search("Synopsis_en", $cFields)] = $item['synopsis_en'];
                $itemExcel[array_search("tagline_en", $cFields)] = $item['tagline_en'];
                $itemExcel[array_search("logline_en", $cFields)] = $item['logline_en'];

                $itemExcel[array_search("Synopsis_arabe", $cFields)] = $item['synopsis_ar'];
                $itemExcel[array_search("tagline_ar", $cFields)] = $item['tagline_ar'];
                $itemExcel[array_search("logline_ar", $cFields)] = $item['logline_ar'];
                $itemExcel[array_search("Casting", $cFields)] = implode(", ",$item['casting']);
                $itemExcel[array_search("Recompenses", $cFields)] = $item['reward'];
                $itemExcel[array_search("Audience", $cFields)] = $item['audience'];
                $itemExcel[array_search("PrixNomination", $cFields)] = $item['award'];
                $itemExcel[array_search("@adresseImages", $cFields)] = $item['image'];
                $itemExcel[array_search("Langue", $cFields)] = $item['language'];
                $itemExcel[array_search("Version", $cFields)] = implode(", ",$item['versions']);
                $itemExcel[array_search("Trailer", $cFields)] = $item['trailer'];
                $itemExcel[array_search("Ep1", $cFields)] = $item['episode1'];
                $itemExcel[array_search("Ep2", $cFields)] = $item['episode2'];
                $itemExcel[array_search("Ep3", $cFields)] = $item['episode3'];
                
                return $itemExcel;
            }, $formattedData);

            array_unshift($excelData,$cFields);

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
            ->setCreator($this->getParameter('app.site')['name'])
            ->setLastModifiedBy("Zacharie Assagou")
            ->setTitle("Catalogue généré par le backend")
            ->setSubject("Catalogue FSA")
            ->setDescription(
                "ce document est un catalogue de production audiovisuel, généré automatique par le backend de Zacharie Assagou pour le compte de la société Côte Ouest Audiovisuel."
            )
            ->setKeywords("catalogue fsa esa php")
            ->setCategory("FSA");

            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(27);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(50);
            $spreadsheet->getActiveSheet()->getStyle('P')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(50);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($excelData,'-','A1');

            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="catalogue.xlsx"');
            header('Cache-Control: max-age=0');
            return $writer->save('php://output');
        }
        else{
            return $this->render('admin/export/catalog.html.twig',array(
                "formattedData"=>$formattedData,
                "catalogHeader"=>$cFields,
            ));
        }
    }

    /**
    * @Route("/watch-link/{token}", name="watch_link")
    * @Method({"GET"})
    */
    public function watchLinkAction(Request $request,$token){
        if($this->isGranted('ROLE_CATALOG_INSERT') || $this->isGranted('ROLE_OBSERVER_CATALOG') || $this->isGranted('ROLE_OBSERVER'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Catalog::class);

        if(!($catalog = $rep->findOneByToken($token))){
            throw $this->createNotFoundException('le catalogue est introuvable');
        }

        $params = $catalog->getCriteria();
        unset($params["_token"]);
        $params["catalog_token"] = $token;
        return $this->forward("AppBundle:AdminExport:preview",[],$params);
    }

    /**
    * @Route("/save", name="save")
    * @Method({"POST","get"})
    */
    public function saveAction(Request $request){
        if($this->isGranted('ROLE_CATALOG_INSERT'));
        else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'êtes as autorisé à consulter cette page");
        }

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Movie::class);
        $result = ["status"=>false];

        $params = $request->query->all();



        if(($data = $rep->count($params))){

            $params = array_filter($params,function($el){
                return strip_tags(trim($el));
            });
                
            $item = new Catalog();
            $item->setCreator($this->getUser());
            $item->setCriteria($params);
            $item->setType("FSA");
            $item->setToken(\AppBundle\Entity\User::generateToken(16));
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute("admin_export_watch_link",['token'=>$item->getToken()]);
            /*
            $result["message"] = "Votre lien de visionnage à bien été créee, vous pour dès maintenant le partager. la durée de validité est de 1 semaine.";
            $result["status"] = true;*/
        }

        return $this->json($result);
    }
}
