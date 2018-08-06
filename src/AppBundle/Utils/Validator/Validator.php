<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\EventDispatcher;

abstract class Validator extends EventDispatcher{
	
	public function __construct(){ }

	public final function process($value){
		$this->validate($value);
	}

	abstract public function validate($value);
}