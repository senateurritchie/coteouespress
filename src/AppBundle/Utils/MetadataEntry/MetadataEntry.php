<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\EventDispatcher;

abstract class MetadataEntry extends EventDispatcher{
	/**
	* la valeur brute d'une cellule
	* @var mixed
	*/
	protected $value;
	/**
	* la valeur d'une cellule apres application des filtres de tranformation.
	* @var mixed
	*/
	protected $filtered;
	
	public function __construct($value=null,$filtered=null){ 
		$this->value = $value;
		$this->filtered = $filtered;
	}

	public function setValue($value){
		$this->value = $value;
		return $this;
	}
	public function getValue(){
		return $this->value;
	}

	public function setFiltered($filtered){
		$this->filtered = $filtered;
		return $this;
	}
	public function getFiltered(){
		return $this->filtered;
	}

	public function __toString(){
		return $this->value;
	}

}