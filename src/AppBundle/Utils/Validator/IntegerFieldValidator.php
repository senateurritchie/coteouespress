<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class IntegerFieldValidator extends FieldValidator{

	public function validate($value){
		return is_numeric($value);
	}
}