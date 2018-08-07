<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\EventDispatcher;
use AppBundle\Utils\Filter\Filter;

class FilterManager extends EventDispatcher{

	/**
	* stock l'ensemble des mise en forme à appliquer sur une cellule
	* @var array
	*/
	protected $data;

	public function __construct(){
		$this->data = array();
	}

	public function add(Filter $item){
		if(!in_array($item, $this->data)){
			$this->data[] = $item;
		}
		return $this;
	}

	public function remove(Filter $item){
		if(false === ($pos = array_search($item, $this->data))) {
			unset($this->data[$pos]);
		}
		return $this;
	}

	public function process($value){
		foreach ($this->data as $key => $item) {
			$value = $item->format($value);
		}
		return $value;
	}
}