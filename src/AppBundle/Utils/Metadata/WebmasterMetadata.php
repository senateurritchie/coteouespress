<?php
namespace AppBundle\Utils\Metadata;

use AppBundle\Utils\Metadata\Metadata;
use AppBundle\Utils\Metadata\HeaderValidator\WebmasterHeaderValidator;

class WebmasterMetadata extends Metadata{

	public function __construct($path,$options){

		$em = $options['entity_manager'];

		$headerValidators 	= [new WebmasterHeaderValidator()];
		$bodyValidators 	= [
			new \AppBundle\Utils\Validator\TextFieldValidator("name",["nullable"=>false,"filters"=>[new \AppBundle\Utils\Filter\TitleFilter()]]),
			
			new \AppBundle\Utils\Validator\TextFieldValidator("originalName",["nullable"=>false,"filters"=>[new \AppBundle\Utils\Filter\TitleFilter()]]),

			new \AppBundle\Utils\Validator\EntityFieldValidator("category",["nullable"=>false,"class"=>\AppBundle\Entity\Category::class,"entity_manager"=>$em,"table_name"=>"Catégories"]),

			new \AppBundle\Utils\Validator\EntityFieldValidator("language",["class"=>\AppBundle\Entity\OriginalLanguage::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues d'origine"]),


			new \AppBundle\Utils\Validator\IntegerFieldValidator("episodeNbr",["nullable"=>false]),

			new \AppBundle\Utils\Validator\IntegerFieldValidator("duration",["nullable"=>false]),

			new \AppBundle\Utils\Validator\ChoiceFieldValidator("mention",["4k","2k","HD","SD"]),

			new \AppBundle\Utils\Validator\DateFieldValidator("year",["nullable"=>false]),

			new \AppBundle\Utils\Validator\UrlFieldValidator("trailer",["filters"=>[new \AppBundle\Utils\Filter\LinkyfyFilter(["attributes"=>["target"=>"_blank"]])]]),

			new \AppBundle\Utils\Validator\UrlFieldValidator("episodes",["multiple"=>true,"filters"=>[new \AppBundle\Utils\Filter\LinkyfyFilter(["attributes"=>["target"=>"_blank"]])]]),

			new \AppBundle\Utils\Validator\TextFieldValidator("synopsis",["filters"=>[new \AppBundle\Utils\Filter\TruncateFilter(10),new \AppBundle\Utils\Filter\TitleFilter()]]),


			new \AppBundle\Utils\Validator\EntityFieldValidator("versions",["class"=>\AppBundle\Entity\Language::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues"]),

        	new \AppBundle\Utils\Validator\EntityFieldValidator("genres",["class"=>\AppBundle\Entity\Genre::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Genres"]),

        	new \AppBundle\Utils\Validator\EntityFieldValidator("countries",["class"=>\AppBundle\Entity\Country::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Pays"]),

        	new \AppBundle\Utils\Validator\EntityFieldValidator("casting",["class"=>\AppBundle\Entity\Actor::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Casting"]),

        	new \AppBundle\Utils\Validator\EntityFieldValidator("producers",["class"=>\AppBundle\Entity\Producer::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Producteurs"]),

        	new \AppBundle\Utils\Validator\EntityFieldValidator("directors",["class"=>\AppBundle\Entity\Director::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Réalisateurs"]),

        	new \AppBundle\Utils\Validator\ChoiceFieldValidator("InTheather",["yes","no"]),

        	new \AppBundle\Utils\Validator\ChoiceFieldValidator("Exclusivity",["yes","no"]),

       		new \AppBundle\Utils\Validator\ChoiceFieldValidator("Published",["yes","no"]),

        	new \AppBundle\Utils\Validator\ImageFieldValidator("@coverImg",["width"=>1920,"height"=>1080]),

        	new \AppBundle\Utils\Validator\ImageFieldValidator("@landscapeImg",["width"=>640,"height"=>360]),

        	new \AppBundle\Utils\Validator\ImageFieldValidator("@portraitImg",["width"=>270,"height"=>360]),

        	new  \AppBundle\Utils\Validator\ImageFieldValidator("@gallery",["width"=>640,"height"=>360])
		];

		parent::__construct($path,$headerValidators,$bodyValidators,$options);
		$this->setDefaultSheetname("Full Video");

		$this->sniffEvents();
	}

	public function sniffEvents(){

		$this
        ->on("data",function($event)use(&$data){
        	
        	$em = $this->getOption("entity_manager");
			$trans = $this->getOption("translator");

        	$field = $event->getHeader();

        	$movie = new \AppBundle\Entity\Movie();

            foreach ($event->getValue() as $el) {
            	if(!trim($el->getValue())) continue;

                if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataResourceEntry){
                    $resources = $el->getResources();
                    foreach ($resources as $i=>$resource) {
                        list($im,$filename) = $resource;
                        // on enregistre l'image
                    }                   
                }
                else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataEntityEntry){
                    $choices = $el->getChoices();
                    foreach ($choices as $i=>$choice) {
                        switch (strtolower($header)) {
	                		case 'category':
	                			$movie->setCategory($choice);
	                		break;

	                		case 'language':
	                			$movie->setLanguage($choice);
	                		break;
	                	}
                    }                   
                }
                else if($el instanceof \AppBundle\Utils\MetadataEntry\MetadataChoiceEntry){
                    $choices = $el->getChoices();
                    foreach ($choices as $i => $choice) {

                        switch (strtolower($header)) {
	                		case 'episodes':
	                			$method = "setEpisode_".($i+1);
	                			$movie->$method($choice);
	                		break;
	                	}
                    }                   
                }
                else{
                	switch (strtolower($header)) {
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
                			$movie->setYear_start($el->getStart());
                			$movie->setYear_end($el->getEnd());
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

            $em->persist($movie);
            $em->flush();
        });
	}
	
}