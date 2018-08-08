<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataChoiceEntry;

class MetadataEntityEntry extends MetadataChoiceEntry{

	public function __construct(){ 
		parent::__construct(self::CHOICE_TYPE_ENTITY);
	}

}