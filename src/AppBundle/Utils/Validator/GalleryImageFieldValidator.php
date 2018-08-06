<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;
use AppBundle\Utils\Validator\FieldValidatorManager;

class GalleryImageFieldValidator extends FieldValidator{
	/**
	 * les options de l'image
	 * @var array
	 */
	protected $options = array(
		"width"=>null,
		"minWidth"=>null,
		"maxWidth"=>null,
		"height"=>null,
		"minHeight"=>null,
		"maxHeight"=>null,
		"allowSquare"=>true,
		"allowLandscape"=>true,
		"allowPortrait"=>true,
		"mimeType"=>null,
	);

	public function __construct($field,array $options){
		parent::__construct($field);
		$this->options = array_merge($this->$options,$options);
	}

	public function validate($value){

		foreach ($value as $key => $el) {
			$validator = new ImageFieldValidator('gallery',$this->options);
			$fm = new FieldValidatorManager();
			$fm->add($validator);
			if(false === ($fm->process('gallery',$el))){
				return false;
			}
		}
		return true;
	}
}