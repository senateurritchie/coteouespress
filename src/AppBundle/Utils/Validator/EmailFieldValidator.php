<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class EmailFieldValidator extends FieldValidator{
	
	public function validate($value){
		return filter_var($value,FILTER_VALIDATE_EMAIL) ? true : false;
	}
}