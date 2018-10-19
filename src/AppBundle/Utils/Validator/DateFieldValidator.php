<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class DateFieldValidator extends FieldValidator{
	
	public function __construct($field,array $options = array()){
		parent::__construct($field,$options);
	}

	public function validate($value){
		try {
			$values = explode("-", $value);
			$values = preg_split("#[/-]#",  $value);


			$values = array_filter($values,function($el){
				return trim($el);
			});

			if(count($values) == 1){
				$value = substr($value, 0,4);

				$date = new \Datetime($value);
				$format = $this->getOption("format");

				if($format){
					if($date->format($format) != $value){
						return "le format de date donné n'est pas valide ";
					}
				}
				$this->emit('validated',[$date,null]);
			}
			else if(count($values) == 2){
				$values[0] = substr($values[0], 0,4);
				$values[1] = substr($values[1], 0,4);

				$start 	= new \Datetime($values[0]);
				$end 	= new \Datetime($values[1]);
				$this->emit('validated',[$start,$end]);
			}
			else{
				return "le format de date donné n'est pas valide ";
			}

			return true;
		} catch (\Exception $e) {
			
		}
		return false;
	}
}