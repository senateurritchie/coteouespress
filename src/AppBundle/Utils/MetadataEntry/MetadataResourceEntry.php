<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataResourceEntry extends MetadataEntry{
	/**
	* donnée réel de la ressource.
	* @var mixed
	*/
	protected $resources;
	
	public function __construct(){ 
		parent::__construct();
		$this->resources = [];
	}

	public function addResource($data,$filename){
		$this->resources[] = [$data,$filename];
		return $this;
	}
	public function getResources(){
		return $this->resources;
	}
}