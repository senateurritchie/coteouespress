<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\EventDispatcher;
use AppBundle\Utils\Exception\ValidatorException;

class ValidatorManager extends EventDispatcher{

	/**
	* stock l'ensemble des validateurs à appliquer sur une cellule
	* @var array
	*/
	protected $data;

	public function __construct(){
		$this->data = array();
	}

	public function add($item){
		if(!in_array($item, $this->data)){
			$this->data[] = $item;
		}
		return $this;
	}

	public function remove($item){
		if(false === ($pos = array_search($item, $this->data))) {
			unset($this->data[$pos]);
		}
		return $this;
	}

	public function process($value){
		
		foreach ($this->data as $key => $item) {
			$ret = $item->validate($value);
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