<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\EventDispatcher;

abstract class Filter extends EventDispatcher{
	/**
	 * le nom identificateur du filter
	 * @var string
	 */
	protected $name;

	public function __construct($name){
		$this->name = $name;
	}

	public final function process($value){
		$this->format($value);
	}

	public function getName(){
		return $this->name;
	}

	abstract public function format($value);
}