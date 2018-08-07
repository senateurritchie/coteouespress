<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class UppercaseFilter extends Filter{

	public function __construct(){
		parent::__construct("uppercase");
	}

	public function format($value){
		return mb_strtoupper($value);
	}
}