<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;
use AppBundle\Utils\Validator\FieldValidatorManager;

class EntityFieldValidator extends FieldValidator{
	/**
	 * les options 
	 * @var array
	 */
	protected $options = array(
		"entity_class"=>null,
		"entity_manager"=>null,
		"query_builder"=>null,
	);

	public function __construct($field,$entityClass,$entityManager){
		parent::__construct($field);
		$this->options = array_merge($this->$options,$options);
	}

	public function validate($value){
		$data = [];

		if(is_callable($this->options['query_builder'])) {

		}
		else if($this->options['entity_manager']) {

		}
		return true;
	}
}