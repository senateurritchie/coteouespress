<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class LowercaseFilter extends Filter{

	public function __construct(){
		parent::__construct("lowercase");
	}

	public function format($value){
		return mb_strtolower($value);
	}
}