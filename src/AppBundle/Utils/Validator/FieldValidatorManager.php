<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\Validator\ValidatorManager;
use AppBundle\Utils\Exception\ValidatorException;

class FieldValidatorManager extends ValidatorManager{

	public function __construct(){
		parent::__construct();
	}

	public function process($field,$value,$zipArchive){

		foreach ($this->data as $key => $item) {
			if($field != $item->getMappedBy()) continue;

			$ret = $item->validate($value,$zipArchive);
			$msg;
			if($ret !== true){

				if(is_string($ret)){
					$msg = $ret;
				}
				elseif (is_array($value)) {
					$msg = json_encode($value).", n'est pas une value valide";
				}
				else{
					$msg = "$value, n'est pas une value valide";
				}
				
				throw new ValidatorException($msg);
				break;
			}
			
		}

		return true;
	}
}