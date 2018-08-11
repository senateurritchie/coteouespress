<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataCollectionEntry extends MetadataEntry{
	/**
	* le tableau de la collection.
	* @var mixed
	*/
	protected $items;
	
	public function __construct(){ 
		parent::__construct();
		$this->items = [];
	}

	public function preppend($item){
		array_unshift($this->items, $items);
		return $this;
	}

	public function append($item){
		$this->items[] = $item;
		return $this;
	}
	public function getAll(){
		return $this->items;
	}
	public function has($item){
		return in_array($item, $this->items);
	}
}