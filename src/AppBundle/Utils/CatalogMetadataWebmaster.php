<?php
namespace AppBundle\Utils\Metadata;

use AppBundle\Utils\Metadata\Metadata;
use AppBundle\Utils\Validator\MetadataHeaderValidator\WebmasterMetadataHeaderValidator;

class MetadataWebmaster extends Metadata{

	/**
	* Initialize le lecteur de metadonnée catalogue
	* @param string $path represente le chemin du fichier zip
	*/
	public function __construct($path){
		parent::__construct($path, new WebmasterMetadataHeaderValidator());
		$tthis->setDefaultSheetname("Full Video");
	}
}