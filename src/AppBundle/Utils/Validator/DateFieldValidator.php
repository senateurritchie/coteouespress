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
			$date = new Datetime($value);

			if($this->format){
				return ($date->format($this->format) == $value);
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