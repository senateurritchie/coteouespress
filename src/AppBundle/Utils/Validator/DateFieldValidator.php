<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class DateFieldValidator extends FieldValidator{
	/**
	 * le format de date souhaité
	 * @var string
	 */
	protected $format;

	public function __construct($field,$format = null){
		parent::__construct($field);
		$this->format = $format;
	}

	public function validate($value){
		try {
			$values = explode("-", $value);
			$values = array_filter($values,function($el){
				return trim($el);
			});

			if(count($values) == 1){
				$date = new \Datetime($value);

				if($this->format){
					if($date->format($this->format) != $value){
						return "le format de date donné n'est pas valide ";
					}
				}

				$this->emit('validated',[$date,null]);
			}
			else if(count($values) == 2){
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

	public function getFormat(){
		return $this->format;
	}
}