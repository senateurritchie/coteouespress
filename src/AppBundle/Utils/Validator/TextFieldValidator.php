<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class TextFieldValidator extends FieldValidator{

	public function validate($value){
		return true;
	}
}