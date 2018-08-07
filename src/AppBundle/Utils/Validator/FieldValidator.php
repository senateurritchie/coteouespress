<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\Validator\Constraints\ConstraintManager;
use AppBundle\Utils\Filter\FilterManager;
use AppBundle\Utils\Filter\SanitizerFilter;


abstract class FieldValidator extends Validator{
	protected $mappedBy;
	protected $cms;
	protected $fms;
	/**
	 * les options 
	 * @var array
	 */
	protected $options = array(
		"nullable"=>true,
		"constraints"=>[],
		"filters"=>[],
		"cellToProcess"=>"A1",
		"fieldToProcess"=>null,
	);

	public function __construct($field,array $options = array()){
		parent::__construct();
		$this->mappedBy = mb_strtolower($field);
		$this->cms = new ConstraintManager();
		$this->fms = new FilterManager();

		$this->options = array_merge($this->options,$options);

		$this->fms->add(new SanitizerFilter(["html"=>true]));

		if(isset($this->options["constraints"])){
			foreach ($this->options["constraints"] as $el) {
				$this->cms->add($el);
			}
		}

		if(isset($this->options["filters"])){
			foreach ($this->options["filters"] as $el) {
				$this->fms->add($el);
			}
		}
		
	}

	public function addConstraint($item){
		$this->cms->add($item);
	}

	public function removeConstraint($item){
		$this->cms->remove($item);
	}

	public function getMappedBy(){
		return $this->mappedBy;
	}

	public function setOption($key,$value){
		$this->options[$key] = $value;
		return $this;
	}
	public function getOption($key,$default=null){
		return isset($this->options[$key]) ? $this->options[$key] : $default;
	}

	public function processFilters($value){
		return $this->fms->process($value);
	}

	public function validate($value){}

}