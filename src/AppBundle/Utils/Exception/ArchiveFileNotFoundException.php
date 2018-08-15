<?php	
namespace AppBundle\Utils\Exception;

class ArchiveFileNotFoundException extends \Exception{
	protected $filename;

	function __construct($filename,$cellToProcess,$msg=null,$code=null){
		$msg = $msg ? $msg : "l'image $filename est introuvable dans la cellule $cellToProcess";
		parent::__construct($msg,$code);
		$this->filename = $filename;
	}

	public function getFilename(){
		return $this->filename;
	}
}