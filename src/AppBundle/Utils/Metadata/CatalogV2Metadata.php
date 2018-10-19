<?php
namespace AppBundle\Utils\Metadata;

use AppBundle\Utils\Metadata\Metadata;
use AppBundle\Utils\Metadata\HeaderValidator\CatalogV2HeaderValidator;

use AppBundle\Utils\Validator\TextFieldValidator;
use AppBundle\Utils\Validator\EntityFieldValidator;
use AppBundle\Utils\Validator\ChoiceFieldValidator;
use AppBundle\Utils\Validator\ImageFieldValidator;
use AppBundle\Utils\Validator\UrlFieldValidator;
use AppBundle\Utils\Validator\DateFieldValidator;
use AppBundle\Utils\Validator\IntegerFieldValidator;

use AppBundle\Utils\Filter\TitleFilter;


use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Entity\OriginalLanguage;
use AppBundle\Entity\Language;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Country;
use AppBundle\Entity\Actor;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Director;
use AppBundle\Entity\MovieLanguage;
use AppBundle\Entity\MovieGenre;
use AppBundle\Entity\MovieActor;
use AppBundle\Entity\MovieCountry;
use AppBundle\Entity\MovieProducer;
use AppBundle\Entity\MovieDirector;
use AppBundle\Entity\MovieScene;
use AppBundle\Entity\MovieCatalog;
use AppBundle\Entity\CatalogSection;
use AppBundle\Entity\CatalogSectionCategory;

class CatalogV2Metadata extends Metadata{

	public function __construct($path,$options){

		$em = $options['entity_manager'];

		$headerValidators 	= [new CatalogV2HeaderValidator()];

		$bodyValidators 	= [
			new TextFieldValidator("TitreExploitation",["nullable"=>false,"filters"=>[new TitleFilter()]]),
			new TextFieldValidator("TitreVO",["nullable"=>false,"filters"=>[new TitleFilter()]]),

            new EntityFieldValidator("Section",["nullable"=>false,"class"=>CatalogSection::class,"entity_manager"=>$em,"table_name"=>"Sections"]),

            new EntityFieldValidator("Section Categorie",["nullable"=>false,"class"=>CatalogSectionCategory::class,"entity_manager"=>$em,"table_name"=>"Sections Catégorie"]),

            new EntityFieldValidator("Categorie",["nullable"=>false,"class"=>Category::class,"entity_manager"=>$em,"table_name"=>"Catégories"]),
			new EntityFieldValidator("Langue",["class"=>OriginalLanguage::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues d'origine"]),
			new IntegerFieldValidator("NombreEpisodes",["nullable"=>false]),
			new IntegerFieldValidator("Durée",["nullable"=>false]),
			new ChoiceFieldValidator("Mention",["4k","2k","HD","SD"]),
			new DateFieldValidator("AnneeProduction",["nullable"=>true]),
            new UrlFieldValidator("Trailer"),
            new UrlFieldValidator("Extrait"),
            new UrlFieldValidator("Ep1"),
            new UrlFieldValidator("Ep2"),
            new UrlFieldValidator("Ep3"),
			new EntityFieldValidator("Version",["class"=>Language::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues"]),
        	new EntityFieldValidator("Genre",["class"=>Genre::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Genres"]),
        	new EntityFieldValidator("OrigineProduction",["class"=>Country::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Pays"]),
        	new EntityFieldValidator("Casting",["class"=>Actor::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Casting","createIfNotExists"=>true]),
        	new EntityFieldValidator("Producteur",["class"=>Producer::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Producteurs","createIfNotExists"=>true]),
            new EntityFieldValidator("Realisateur",["class"=>Director::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Réalisateurs","createIfNotExists"=>true]),
            new EntityFieldValidator("Catalogues",["class"=>\AppBundle\Entity\CatalogType::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Catalogues"]),
        	new ImageFieldValidator("@ImagesWeb",["width"=>270,"height"=>360]),
		];

		parent::__construct($path,$headerValidators,$bodyValidators,$options);
		$this->setDefaultSheetname("Full Video");
	}

    public function onData(\AppBundle\Utils\Event\Event $event){

    	$em = $this->getOption("entity_manager");
        $trans = $this->getOption("translator");
        $validator = $this->getOption("validator");
        $upload_dir = $this->getOption("upload_dir");

    	$movie = new Movie();

        $movie->setInsertMode(Movie::INSERT_MODE_META_CATALOG);
        $movie->setState(Movie::STATE_PREMODERATE);

    	$fields = $this->getSheetHeader();
        $empty_cell = 0;
        $versions = [];
        $genres = [];
        $countries = [];
        $castings = [];
        $producers = [];
        $directors = [];
        $scenes = [];
        $catalogs = [];

        foreach ($event->getValue() as $pos_f => $el) {
        	$field = $fields[$pos_f];

        	if(!trim($el->getValue())){
                $empty_cell++;
                continue;
            }

            if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataImageEntry){
                $image = imagecreatefromstring($el->getRaw());

                $fileName = md5(uniqid()).'.jpg';
                $path = $upload_dir.'/'.$fileName;

                imagejpeg($image,$path);
                imagedestroy($image);
                
                switch ($field) {
            		case '@ImagesWeb':
            			$movie->setPortraitImg($fileName);
            		break;
            	}                 
            }
            else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataEntityEntry){
                $choices = $el->getChoices();
                foreach ($choices as $i=>$choice) {
                   

                    switch ($field) {

                        case 'Section':
                            $movie->setSection($choice);
                        break;

                        case 'Section Categorie':
                            $movie->setSectionCategory($choice);
                        break;

                		case 'Categorie':
                			$movie->setCategory($choice);
                		break;

                		case 'Langue':
                			$movie->setLanguage($choice);
                		break;

                		// les insertions multiple
                		case 'Version':
                			$versions[] = $choice;
                		break;
                		case 'Genre':
                			$genres[] = $choice;
                		break;
                		case 'OrigineProduction':
                			$countries[] = $choice;
                		break;
                		case 'Casting':
                			$castings[] = $choice;
                		break;
                		case 'Producteur':
                			$producers[] = $choice;
                		break;
                		case 'Realisateur':
                			$directors[] = $choice;
                		break;
                        case 'Catalogues':
                            $catalogs[] = $choice;
                        break;
                	}
                }                   
            }
            else{

            	switch ($field) {
            		case 'TitreExploitation':
            			$movie->setName($el->getValue());
            		break;
            		case 'TitreVO':
            		    $movie->setOriginalName($el->getValue());
            		break;
            		case 'Synopsis_fr':
            		    $movie->setSynopsis($el->getValue());
            		break;
                    case 'Tagline_fr':
                        $movie->setTagline($el->getValue());
                    break;
                    case 'Logline_fr':
                        $movie->setLogline($el->getValue());
                    break;

            		case 'Format':
            		    $movie->setFormat($el->getValue());
            		break;
            		case 'Mention':
            		    $movie->setMention($el->getValue());
            		break;

            		case 'Trailer':
            		    $movie->setTrailer($el->getValue());
            		break;
                    case 'Ep1':
                        $movie->setEpisode1($el->getValue());
                    break;
                    case 'Ep2':
                        $movie->setEpisode2($el->getValue());
                    break;
                    case 'Ep3':
                        $movie->setEpisode3($el->getValue());
                    break;

            		case 'Recompenses':
            		    $movie->setReward($el->getValue());
            		break;
            		case 'PrixNomination':
            			$movie->setAward($el->getValue());
            		break;
            		case 'Audience':
            			$movie->setAudience($el->getValue());
            		break;

            		//les dates
            		case 'AnneeProduction':
            			$movie->setYearStart($el->getStart());
            			$movie->setYearEnd($el->getEnd());
            		break;

                    case 'Durée':
                        $movie->setFormat(trim($el->getValue()));
                    break;

                    case 'NombreEpisodes':
                        $duration = intval(trim($movie->getFormat()));
                        $eps = intval(trim($el->getValue()));
                        $format = $eps."x".$duration."'";
                        $movie->setFormat($format);
                    break;

            		// traductions
            		case 'Synopsis_en':
            			$trans->translate($movie, 'synopsis', 'en',$el->getValue());
            		break;
            		case 'Synopsis_arabe':
            			$trans->translate($movie, 'synopsis', 'ar',$el->getValue());
            		break;
            		case 'Tagline_en':
            		    $trans->translate($movie, 'tagline', 'en',$el->getValue());
            		break;
            		case 'Tagline_ar':
            			$trans->translate($movie, 'tagline', 'ar',$el->getValue());
            		break;
            	}
            }
        }
        if($empty_cell != count($fields)) {
            
            $em->persist($movie);

            // les versions du programme
            $db = [];
            if(count($versions)){
            	foreach ($versions as $key => $el) {
	                $e = new MovieLanguage();
	                $e->setMovie($movie);
	                $e->setLanguage($el);
	                $em->persist($e);
                    $db[] = $el->getId();
	            }
            }

            // les genres du programme
            $db = [];
            if(count($genres)){
            	foreach ($genres as $key => $el) {
	                $e = new MovieGenre();
	                $e->setMovie($movie);
	                $e->setGenre($el);
	                $em->persist($e);
                    $db[] = $el->getId();
	            }
            }

            // les pays de productions du programme
            $db = [];
            foreach ($countries as $key => $el) {
                $e = new MovieCountry();
                $e->setMovie($movie);
                $e->setCountry($el);
                $em->persist($e);
                $db[] = $el->getId();
            }

            // les acteurs du programme
            $db = [];
            foreach ($castings as $key => $el) {
                $e = new MovieActor();
                $e->setMovie($movie);
                $e->setActor($el);
                $em->persist($e);
                $db[] = $el->getId();
            }
            
            // les producteurs du programme
            $db = [];
            foreach ($producers as $key => $el) {
                $e = new MovieProducer();
                $e->setMovie($movie);
                $e->setProducer($el);
                $em->persist($e);
                $db[] = $el->getId();
            }

            // les réalisateurs du programme
            $db = [];
            foreach ($directors as $key => $el) {
                $e = new MovieDirector();
                $e->setMovie($movie);
                $e->setDirector($el);
                $em->persist($e);
                $db[] = $el->getId();
            }

            // la gallery photo du programme
            foreach ($scenes as $key => $el) {
            	$image = imagecreatefromstring($el->getRaw());

                $fileName = md5(uniqid()).'.jpg';
                $path = $upload_dir.'/'.$fileName;

                $e = new MovieScene();
                $e->setMovie($movie);
                $e->setImage($fileName);
                $em->persist($e);

                imagejpeg($image,$path);
                imagedestroy($image);
            }

            // gestion des catalogues
            $db = [];
            foreach ($catalogs as $key => $el) {
                $e = new MovieCatalog();
                $e->setMovie($movie);
                $e->setCatalog($el);
                $em->persist($e);
                $db[] = $el->getId();
            }
        }
	}

    public function onCellData(\AppBundle\Utils\Event\Event $event){
        
    }

    public function onHeaderData(\AppBundle\Utils\Event\Event $event){

    }
}