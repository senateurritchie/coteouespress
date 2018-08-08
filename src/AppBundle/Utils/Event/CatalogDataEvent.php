<?php	
namespace AppBundle\Utils\Event;

use AppBundle\Utils\Event\Event;

class CatalogDataEvent extends Event{
	protected $header;
	
	function __construct($header,$value){
		parent::__construct("data",$value);
		$this->header = $header;
	}

	public function getHeader(){
		return $this->header;
	}
}