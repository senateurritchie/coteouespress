<?php	
namespace AppBundle\Utils\Event;

use AppBundle\Utils\Event\Event;

class CatalogDataEvent extends Event{
	protected $archive;
	protected $header;

	function __construct($archive,$header,$value){
		parent::__construct("data",$value);
		$this->archive = $archive;
		$this->header = $header;
	}

	public function getArchive(){
		return $this->archive;
	}

	public function getHeader(){
		return $this->header;
	}
}