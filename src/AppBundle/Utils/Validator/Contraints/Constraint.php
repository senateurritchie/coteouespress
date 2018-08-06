<?php
namespace AppBundle\Utils\Validator\Constraints;

use AppBundle\Utils\Validator\Validator;

abstract class Constraint extends Validator{
	public function __construct(){ 
		parent::__construct();
	}
}