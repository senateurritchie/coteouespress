<?php
namespace AppBundle\Utils\Metadata\HeaderValidator;
use AppBundle\Utils\Validator\Validator;

abstract class HeaderValidator extends Validator{
	/**
	* les entêtes predefinies à valider
	* @var array
	*/
	protected $fields;

	public function __construct(array $fields = []){
		parent::__construct();
		$this->fields = $fields;
	}

	public function validate($value){

		$msg_error = "l'entête du fichier excel n'est pas valide. Utilisez l'entête suivante: ".implode(", ", $this->fields);


		if(count($this->fields) != count($value)){
			return $msg_error;
		}

		foreach ($this->fields as $key => $el) {
			if(mb_strtolower($value[$key]) != mb_strtolower($el)){
				return $msg_error;
			};
		}
		return true;
	}

	public function getFields(){
		return $this->fields;
	}
}