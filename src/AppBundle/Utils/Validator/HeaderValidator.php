<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\Validator;

class HeaderValidator extends Validator{
	/**
	* les entêtes predefinies à valider
	* @var array
	*/
	protected $fields = [
		"Name","Category","EpisodeNbr","Duration","Mention","Trailer","Episode1","Episode2","Episode3","Synopsis","Tagline","Logline","Rewards","Awards","Audiences","Languages","Genres","Countries","Casting","Producers","Directors","In Theather","Exclusivity","Published","@CoverImg","@LandscapeImg","@PortraitImg","@Gallery"];


	public function __construct(array $fields = []){
		parent::__construct();
		$this->fields = count($fields) ? $fields : $this->fields;
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