<?php
namespace AppBundle\Utils\Metadata;

use AppBundle\Utils\Metadata\Metadata;
use AppBundle\Utils\Metadata\HeaderValidator\WebmasterHeaderValidator;

class WebmasterMetadata extends Metadata{

	public function __construct($path){
		$headerValidators 	= [new WebmasterHeaderValidator()];
		$bodyValidators 	= [
			new \AppBundle\Utils\Validator\TextFieldValidator("name",["nullable"=>false,"filters"=>[new \AppBundle\Utils\Filter\TitleFilter()]]),
			new \AppBundle\Utils\Validator\IntegerFieldValidator("episodeNbr",["nullable"=>false]),
			new \AppBundle\Utils\Validator\IntegerFieldValidator("duration",["nullable"=>false]),
			new \AppBundle\Utils\Validator\ChoiceFieldValidator("mention",["4k","2k","HD","SD"]),
			new \AppBundle\Utils\Validator\DateFieldValidator("year",["nullable"=>false]),
			new \AppBundle\Utils\Validator\UrlFieldValidator("trailer",["filters"=>[new \AppBundle\Utils\Filter\LinkyfyFilter(["attributes"=>["target"=>"_blank"]])]]),
			new \AppBundle\Utils\Validator\UrlFieldValidator("episodes",["multiple"=>true,"filters"=>[new \AppBundle\Utils\Filter\LinkyfyFilter(["attributes"=>["target"=>"_blank"]])]]),
			new \AppBundle\Utils\Validator\TextFieldValidator("synopsis",["filters"=>[new \AppBundle\Utils\Filter\TruncateFilter(10),new \AppBundle\Utils\Filter\TitleFilter()]]),
			new \AppBundle\Utils\Validator\EntityFieldValidator("category",["nullable"=>false,"class"=>\AppBundle\Entity\Category::class,"entity_manager"=>$em,"table_name"=>"Catégories"]),

			new \AppBundle\Utils\Validator\EntityFieldValidator("languages",["class"=>\AppBundle\Entity\Language::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Langues"])
        	new \AppBundle\Utils\Validator\EntityFieldValidator("genres",["class"=>\AppBundle\Entity\Genre::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Genres"])
        	new \AppBundle\Utils\Validator\EntityFieldValidator("countries",["class"=>\AppBundle\Entity\Country::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Pays"])
        	new \AppBundle\Utils\Validator\EntityFieldValidator("casting",["class"=>\AppBundle\Entity\Actor::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Casting"])
        	new \AppBundle\Utils\Validator\EntityFieldValidator("producers",["class"=>\AppBundle\Entity\Producer::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Producteurs"])
        	new \AppBundle\Utils\Validator\EntityFieldValidator("directors",["class"=>\AppBundle\Entity\Director::class,"entity_manager"=>$em,"multiple"=>true,"table_name"=>"Réalisateurs"])
        	new \AppBundle\Utils\Validator\ChoiceFieldValidator("In Theather",["yes","no"])
        	new \AppBundle\Utils\Validator\ChoiceFieldValidator("Exclusivity",["yes","no"])
       		new \AppBundle\Utils\Validator\ChoiceFieldValidator("Published",["yes","no"])

        	new \AppBundle\Utils\Validator\ImageFieldValidator("@coverImg",["width"=>1920,"height"=>1080])
        	new \AppBundle\Utils\Validator\ImageFieldValidator("@landscapeImg",["width"=>640,"height"=>360])
        	new \AppBundle\Utils\Validator\ImageFieldValidator("@portraitImg",["width"=>270,"height"=>360])
        	new  \AppBundle\Utils\Validator\ImageFieldValidator("@gallery",["width"=>640,"height"=>360])
		];

		parent::__construct($path,$headerValidators,$bodyValidators);
		$this->setDefaultSheetname("Full Video");
	}
	
}