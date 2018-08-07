<?php
namespace AppBundle\Utils\Filter;

use AppBundle\Utils\Filter\Filter;

class TruncateFilter extends Filter{
	/**
	 * la taille maximum du texte à retourner
	 * @var integer
	 */
	protected $limit;
	/**
	 * le mode de cesure à afficher
	 * @var integer
	 */
	protected $overflow;

	public function __construct($limit,$overflow = '...'){
		parent::__construct("truncate");
		$this->setLimit($limit);
		$this->setOverflow($overflow);
	}

	public function format($value){
		$text = substr($value,0,$this->limit);
		$text = strlen($value) > $this->limit ? $text.$this->overflow : $text;
		return $text;
	}

	public function setLimit($limit){
		$this->limit = $limit;
		return $this;
	}
	public function getLimit(){
		return $this->limit;
	}

	public function setOverflow($overflow='...'){
		$this->overflow = $overflow;
		return $this;
	}
	public function getOverflow(){
		return $this->overflow;
	}
}