<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class ImageFieldValidator extends FieldValidator{
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
		try {
			$image = imagecreatefromstring($value);
			list($width,$height,$mime,$attr) = getimagesizefromstring($value);
			imagedestroy($image);

			if($this->options['width']){
				if($this->options['width'] != $width){
					return "cette image doit avoir une largeur de ".$this->options['minWidth']."px";
				}
			}

			if($this->options['minWidth']){
				if($width >= $this->options['minWidth']);
				else{
					return "cette image doit avoir une largeur minimum de ".$this->options['minWidth']."px";
				}
			}

			if($this->options['maxWidth']){
				if($width <= $this->options['maxWidth']);
				else{
					return "cette image doit avoir une largeur maximum de ".$this->options['maxWidth']."px";
				}
			}

			if($this->options['height']){
				if($this->options['height'] != $height){
					return "cette image doit avoir une hauteur de ".$this->options['height']."px";
				}
			}

			if($this->options['minHeight']){
				if($height >= $this->options['minHeight']);
				else{
					return "cette image doit avoir une hauteur minimum de ".$this->options['minHeight']."px";
				}
			}

			if($this->options['maxHeight']){
				if($height <= $this->options['maxHeight']);
				else{
					return "cette image doit avoir une hauteur maximum de ".$this->options['maxHeight']."px";
				}
			}

			if($this->options['allowSquare'] === false){
				if($width == $height){
					return "les images carrés sont interdites";
				}
			}

			if($this->options['allowPortrait'] === false){
				if($width < $height){
					return "les images en portrait sont interdites";
				}
			}

			if($this->options['allowLandscape'] === false){
				if($width > $height){
					return "les images en paysage sont interdites";
				}
			}

			if($this->options['mime']){
				if(is_string($this->options['mime'])) {
					if(strtolower($this->options['mime']) != strtolower($mine)){
						return "seul les images de type ".$this->options['mime']." sont acceptées";
					}
				}
				else if(is_array($this->options['mime'])) {
					if(!in_array(strtolower($mine), $this->options['mime'])) {
						return "seul les images de types ".implode(", ", $this->options['mime'])." sont acceptées";
					}
				}
			}

			return true;
		} catch (Exception $e) {
			
		}
		return false;
	}
}