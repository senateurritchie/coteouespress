<?php	
namespace AppBundle\Utils\Event;

use AppBundle\Utils\Event\Event;

class CellDataEvent extends Event{
	protected $header;
	
	function __construct($header,$value){
		parent::__construct("cell-data",$value);
		$this->header = $header;
	}

	public function getHeader(){
		return $this->header;
	}
}