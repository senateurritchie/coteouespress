<?php
namespace AppBundle\Utils\MetadataEntry;
use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataPlainTextEntry extends MetadataEntry{
	
	public function __construct(){ 
		parent::__construct();
	}

	public function __toString(){
		return $this->value;
	}
}