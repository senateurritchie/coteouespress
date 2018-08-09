<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataDateEntry extends MetadataEntry{
	/**
	* donnée réel de la ressource.
	* @var arra
	*/
	protected $start;
	protected $end;
	
	public function __construct(){ 
		parent::__construct();
	}

	public function setRange(array $data){
		list($this->start,$this->end) = $data;
		return $this;
	}

	public function getStart(){
		return $this->start;
	}
	public function getEnd(){
		return $this->end;
	}
}