<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class BooleanFieldValidator extends FieldValidator{
	
	public function validate($value){
		return is_bool($value);
	}
}