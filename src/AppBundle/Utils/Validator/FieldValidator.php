<?php
namespace AppBundle\Utils\Validator;

use AppBundle\Utils\Constraint\ConstraintManager;

class FieldValidator extends Validator{
	protected $mappedBy;
	protected $cms;
	protected $archive;

	public function __construct($field,array $constraints=array(),\ZipArchive $archive = null){
		parent::__construct();
		$this->mappedBy = $field;
		$this->archive = $archive;
		$this->cms = new ConstraintManager();

		foreach ($constraints as $el) {
			$this->cms->add($el);
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
}