<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class TextFieldValidator extends FieldValidator{

	public function validate($value){
		if($this->getOption('nullable') === false && !$value){
			$cell = $this->options['cellToProcess'];
			return "[$this->mappedBy] ne peut être vide, dans la cellule $cell";
		} 
		return true;
	}
}