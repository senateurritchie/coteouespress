<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\Validator;

class CatalogMetadataHeaderValidator extends Validator{
	/**
	* les entêtes predefinies à valider
	* @var array
	*/
	protected $fields = [
		"N°","Nom & Prénoms","Civilité","Naissance","Téléphone","Lieu d'habitation","Tribu",
		"STATUT MATRIMONIAL","@img1920x1080","@img640x360","@img270x360"];

	public function __construct(array $value = [],array $fields = []){
		$this->fields = count($fields) ? $fields : $this->fields;

		parent::__construct($value);
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
}