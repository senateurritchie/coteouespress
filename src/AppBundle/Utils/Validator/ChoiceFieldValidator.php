<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class ChoiceFieldValidator extends FieldValidator{
	/**
	 * les differents choix possible pour une valeur donnée
	 * @var array
	 */
	protected $choices;

	public function __construct($field,array $choices){
		parent::__construct($field);

		foreach ($choices as $key => &$el) {
			$el = mb_strtoupper($el);
		}
		unset($el);
		$this->choices = $choices;
	}

	public function validate($value){
		return in_array(mb_strtoupper($value), $this->choices);
	}
}