<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class LinkyfyFilter extends Filter{

	protected $options = array(
		"attributes"=>array()
	);

	public function __construct( array $options = array()){
		parent::__construct("linkyfy");
		$this->options = array_merge($this->options,$options);

		if(!is_array($this->options["attributes"])) $this->options["attributes"] = [];
	}

	public function format($value){
		$attributes = "";

		foreach ($this->options["attributes"] as $key => $el) {
			$attributes .= " $key='$el' ";
		}

		return preg_replace("#((?:(?:[a-z]+)://)?(?:w{3})?[^ ;]+)#", "<a $attributes href='$1'>$1</a>",$value);
	}
}