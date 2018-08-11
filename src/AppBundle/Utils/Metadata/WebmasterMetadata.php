<?php
namespace AppBundle\Utils\Metadata;

use AppBundle\Utils\Metadata\Metadata;
use AppBundle\Utils\Metadata\HeaderValidator\WebmasterHeaderValidator;

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

class WebmasterMetadata extends Metadata{

	public function __construct($path,$options){

		$em = $options['entity_manager'];

		$headerValidators 	= [new WebmasterHeaderValidator()];
		$bodyValidators 	= [
			new TextFieldValidator("name",["nullable"=>false,"filters"=>[new TitleFilter()]]),
			new TextFieldValidator("originalName",["nullable"=>false,"filters"=>[new TitleFilter()]]),
			new EntityFieldValidator("category",["nullable"=>false,"class"=>Category::class,"entity_manager"=>$em,"table_name"=>"Catégories"]),
			new EntityFieldValidator("language",["class"=>OriginalLanguage::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues d'origine"]),
			new IntegerFieldValidator("episodeNbr",["nullable"=>false]),
			new IntegerFieldValidator("duration",["nullable"=>false]),
			new ChoiceFieldValidator("mention",["4k","2k","HD","SD"]),
			new DateFieldValidator("year",["nullable"=>false]),
			new UrlFieldValidator("trailer"),
			new UrlFieldValidator("episodes",["multiple"=>true]),
			new TextFieldValidator("synopsis",["nullable"=>false]),
			new EntityFieldValidator("versions",["class"=>Language::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues"]),
        	new EntityFieldValidator("genres",["class"=>Genre::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Genres"]),
        	new EntityFieldValidator("countries",["class"=>Country::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Pays"]),
        	new EntityFieldValidator("casting",["class"=>Actor::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Casting"]),
        	new EntityFieldValidator("producers",["class"=>Producer::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Producteurs"]),
        	new EntityFieldValidator("directors",["class"=>Director::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Réalisateurs"]),
        	new ChoiceFieldValidator("InTheather",["yes","no"]),
        	new ChoiceFieldValidator("Exclusivity",["yes","no"]),
       		new ChoiceFieldValidator("Published",["yes","no"]),
        	new ImageFieldValidator("@coverImg",["width"=>1920,"height"=>1080]),
        	new ImageFieldValidator("@landscapeImg",["width"=>640,"height"=>360]),
        	new ImageFieldValidator("@portraitImg",["width"=>270,"height"=>360]),
        	new ImageFieldValidator("@gallery",["width"=>640,"height"=>360])
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

        $movie->setInsertMode(Movie::INSERT_MODE_META_WEBMASTER);
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
                
                switch (strtolower($field)) {
            		case '@coverimg':
            			$movie->setCoverImg($fileName);
            		break;

            		case '@landscapeimg':
            			$movie->setLandscapeImg($fileName);
            		break;

            		case '@portraitimg':
            			$movie->setPortraitImg($fileName);
            		break;
            	}                 
            }
            else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataCollectionEntry){
                $items = $el->getAll();

                foreach ($items as $i => $item) {

                	switch (strtolower($field)) {
                		case '@gallery':
                			$scenes[] = $item;
                		break;
                	}
                }                   
            }
            else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataEntityEntry){
                $choices = $el->getChoices();
                foreach ($choices as $i=>$choice) {
                    switch (strtolower($field)) {
                		case 'category':
                			$movie->setCategory($choice);
                		break;

                		case 'language':
                			$movie->setLanguage($choice);
                		break;

                		// les insertions multiple
                		case 'versions':
                			$versions[] = $choice;
                		break;
                		case 'genres':
                			$genres[] = $choice;
                		break;
                		case 'countries':
                			$countries[] = $choice;
                		break;
                		case 'casting':
                			$castings[] = $choice;
                		break;
                		case 'producers':
                			$producers[] = $choice;
                		break;
                		case 'directors':
                			$directors[] = $choice;
                		break;


                	}
                }                   
            }
            else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataChoiceEntry){
                $choices = $el->getChoices();
                foreach ($choices as $i => $choice) {

                    switch (strtolower($field)) {
                		case 'episodes':
                			$method = "setEpisode".($i+1);
                			$movie->$method($choice);
                		break;
                	}
                }                   
            }
            else{
            	switch (strtolower($field)) {
            		case 'name':
            			$movie->setName($el->getValue());
            		break;
            		case 'originalname':
            		    $movie->setOriginalName($el->getValue());
            		break;
            		case 'synopsis':
            		    $movie->setSynopsis($el->getValue());
            		break;
            		case 'format':
            		    $movie->setFormat($el->getValue());
            		break;
            		case 'mention':
            		    $movie->setMention($el->getValue());
            		break;
            		case 'trailer':
            		    $movie->setTrailer($el->getValue());
            		break;
            		case 'rewards':
            		    $movie->setReward($el->getValue());
            		break;
            		case 'awards':
            			$movie->setAward($el->getValue());
            		break;
            		case 'audiences':
            			$movie->setAudience($el->getValue());
            		break;

            		//les dates
            		case 'year':
            			$movie->setYearStart($el->getStart());
            			$movie->setYearEnd($el->getEnd());
            		break;

            		// value boolean
            		case 'intheather':
            			$e = ($el->getValue() == "yes")?1:0;
            			$movie->setInTheather($e);
            		break;
            		case 'exclusivity':
            			$e = ($el->getValue() == "yes")?1:0;
            			$movie->setHasExclusivity($e);
            		break;
            		case 'published':
            			$e = ($el->getValue() == "yes")?1:0;
            			$movie->setIsPublished($e);
            		break;

                    case 'episodenbr':
                        $movie->setFormat($el->getValue());
                    break;

                    case 'duration':
                        $eps = intval($movie->getFormat());
                        $duration = intval($el->getValue());
                        $format = $eps."x".$duration."'";
                        $movie->setFormat($format);
                    break;


            		// traductions
            		case 'synopsis_en':
            			$trans->translate($movie, 'synopsis', 'en',$el->getValue());
            		break;
            		case 'synopsis_ar':
            			$trans->translate($movie, 'synopsis', 'ar',$el->getValue());
            		break;
            		case 'tagline_en':
            		    $trans->translate($movie, 'tagline', 'en',$el->getValue());
            		break;
            		case 'tagline_ar':
            			$trans->translate($movie, 'tagline', 'ar',$el->getValue());
            		break;
            	}
            }
        }
        if($empty_cell != count($fields)) {
            $errors = $validator->validate($movie);

            
            $em->persist($movie);

            // les versions du programme
            if(count($versions)){
            	foreach ($versions as $key => $el) {
	                $e = new MovieLanguage();
	                $e->setMovie($movie);
	                $e->setLanguage($el);
	                $em->persist($e);
	            }
            }

            // les genres du programme
            if(count($genres)){
            	foreach ($genres as $key => $el) {
	                $e = new MovieGenre();
	                $e->setMovie($movie);
	                $e->setGenre($el);
	                $em->persist($e);
	            }
            }

            // les pays de productions du programme
            foreach ($countries as $key => $el) {
                $e = new MovieCountry();
                $e->setMovie($movie);
                $e->setCountry($el);
                $em->persist($e);
            }

            // les acteurs du programme
            foreach ($castings as $key => $el) {
                $e = new MovieActor();
                $e->setMovie($movie);
                $e->setActor($el);
                $em->persist($e);
            }
            
            // les producteurs du programme
            foreach ($producers as $key => $el) {
                $e = new MovieProducer();
                $e->setMovie($movie);
                $e->setProducer($el);
                $em->persist($e);
            }

            // les réalisateurs du programme
            foreach ($directors as $key => $el) {
                $e = new MovieDirector();
                $e->setMovie($movie);
                $e->setDirector($el);
                $em->persist($e);
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
           
        }
	}

    public function onCellData(\AppBundle\Utils\Event\Event $event){
        
    }

    public function onHeaderData(\AppBundle\Utils\Event\Event $event){

    }
}