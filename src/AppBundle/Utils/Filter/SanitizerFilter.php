<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class SanitizerFilter extends Filter{
	/**
	 * les differentes options du sanitizer
	 * @var array
	 */
	protected $options = [
		"html"=>true,
		"url"=>false,
	];

	public function __construct(array $options = array()){
		parent::__construct("sanitizer");
		$this->options = array_merge($this->options,$options);
	}

	public function format($value){
		return strip_tags(trim($value));
	}
}