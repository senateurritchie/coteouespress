<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataChoiceEntry extends MetadataEntry{

	const CHOICE_TYPE_TEXT = 'text';
	const CHOICE_TYPE_ENTITY = 'entity';
	const CHOICE_TYPE_IMAGE = 'image';

	/**
	* la liste de valeur.
	* @var array
	*/
	protected $choices;
	/**
	* defini le type d'entité stocké
	* @var string
	*/
	protected $type;
	/**
	* defini si le choix est multiple ou non
	* @var boolean
	*/
	protected $multiple;
	
	public function __construct($type = self::CHOICE_TYPE_TEXT,$is_multiple = false){ 
		parent::__construct();
		$this->choices = [];
		$this->type = $type;
		$this->multiple = $is_multiple;
	}

	public function addChoice($choice){

		if(is_array($choice)){
			foreach ($choice as $key => $el) {
				$this->choices[] = $el;
			}
		}
		else{
			$this->choices[] = $choice;
		}
		return $this;
	}
	public function getChoices(){
		return $this->choices;
	}

	public function setType($type = self::CHOICE_TYPE_TEXT){
		$this->type = $type;
		return $this;
	}
	public function getType(){
		return $this->type;
	}
	public function isMultiple(){
		return $this->is_multiple;
	}
}