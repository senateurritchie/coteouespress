<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\Validator\ValidatorManager;
use AppBundle\Utils\Exception\ValidatorException;

class FieldValidatorManager extends ValidatorManager{
	protected $fieldToProcess;
	protected $cellToProcess;

	public function __construct(){
		parent::__construct();
	}

	public function setFieldToProcess($value){
		$this->fieldToProcess = mb_strtolower($value);
		return $this;
	}

	public function getFieldToProcess(){
		return $this->fieldToProcess;
	}

	public function setCellToProcess($value){
		$this->cellToProcess = $value;
		return $this;
	}
	public function getCellToProcess(){
		return $this->cellToProcess;
	}


	public function process($value){

		$field = $this->getFieldToProcess();

		foreach ($this->data as $key => $item) {
			if($this->getFieldToProcess() != $item->getMappedBy()) continue;
			if($item->getOption('nullable') && !$value) continue;

			if($item->getOption('nullable') === false && !$value){
				$msg = "[$field] ne peut être vide, dans la cellule $this->cellToProcess";
				throw new ValidatorException($msg);
			}

			$item->setOption('cellToProcess',$this->cellToProcess);

			$ret = $item->validate($value);
			$msg;
			if($ret !== true){

				if(is_string($ret)){
					$msg = $ret;
				}
				elseif (is_array($value)) {
					$msg = json_encode($value).", n'est pas une valeur valide dans la cellule $this->cellToProcess";
				}
				else{
					$msg = "[$field], '$value' n'est pas une valeur valide dans la cellule $this->cellToProcess";
				}
				
				throw new ValidatorException($msg);
				break;
			}
		}

		return true;
	}

	public function processFilters($value){
		$field = $this->getFieldToProcess();

		foreach ($this->data as $key => $item) {
			if($field != $item->getMappedBy()) continue;
			if($item->getOption('nullable') && !$value) continue;
			$value = $item->processFilters($value);
		}
		return $value;
	}

	public function processConstraints($value){

	}
}