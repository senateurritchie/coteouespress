<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class TitleFilter extends Filter{

	public function __construct(){
		parent::__construct("title");
	}

	public function format($value){
		return ucwords(mb_strtolower($value));
	}
}