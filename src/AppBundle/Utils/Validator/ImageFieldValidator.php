<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;
use AppBundle\Utils\Exception\ImageFieldValidatorException;

class ImageFieldValidator extends FieldValidator{
	/**
	 * les options de l'image
	 * @var array
	 */
	protected $default_options = array(
		"width"=>null,
		"minWidth"=>null,
		"maxWidth"=>null,
		"height"=>null,
		"minHeight"=>null,
		"maxHeight"=>null,
		"allowSquare"=>true,
		"allowLandscape"=>true,
		"allowPortrait"=>true,
		"mimeType"=>"image/*",
	);

	public function __construct($field,array $options){
		parent::__construct($field,array_merge($this->default_options,$options));
	}

	public function validate($value){

		list($value,$filename) = $value;

		try {
			$image = imagecreatefromstring($value);
			list($width,$height,$mime,$attr) = getimagesizefromstring($value);
			$mime = image_type_to_mime_type($mime);
			imagedestroy($image);

			if($this->options['width']){
				if($this->options['width'] != $width){
					$msg = "l'image '{{ filename }}' doit avoir une largeur de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['width'],
						"given"=>$width,
						"filename"=>$filename,
					]);
				}
			}

			if($this->options['minWidth']){
				if($width >= $this->options['minWidth']);
				else{
					$msg = "l'image '{{ filename }}' doit avoir une largeur minimum de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['minWidth'],
						"given"=>$width,
						"filename"=>$filename,
					]);
				}
			}

			if($this->options['maxWidth']){
				if($width <= $this->options['maxWidth']);
				else{
					$msg = "l'image '{{ filename }}' doit avoir une largeur maximum de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['maxWidth'],
						"given"=>$width,
						"filename"=>$filename,
					]);
				}
			}

			if($this->options['height']){
				if($this->options['height'] != $height){
					$msg = "l'image '{{ filename }}' doit avoir une hauteur de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['height'],
						"given"=>$height,
						"filename"=>$filename,
					]);

				}
			}

			if($this->options['minHeight']){
				if($height >= $this->options['minHeight']);
				else{
					$msg = "l'image '{{ filename }}' doit avoir une hauteur minimum de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['minHeight'],
						"given"=>$height,
						"filename"=>$filename,
					]);

				}
			}

			if($this->options['maxHeight']){
				if($height <= $this->options['maxHeight']);
				else{
					$msg =  "l'image '{{ filename }}' doit avoir une hauteur maximum de {{ expected }}px. une valeur de {{ given }}px à été trouvée";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>$this->options['maxHeight'],
						"given"=>$height,
						"filename"=>$filename,
					]);

				}
			}

			if($this->options['allowSquare'] === false){
				if($width == $height){
					$msg = "l'image '{{ filename }}' est {{ given }}, veuillez uploader une image {{ expected }}";

					throw new ImageFieldValidatorException($msg,[
						"expected"=>"portrait ou paysage",
						"given"=>"carré",
						"filename"=>$filename,
					]);

				}
			}

			if($this->options['allowPortrait'] === false){
				if($width < $height){
					$msg = "l'image '{{ filename }}' est en {{ given }}, veuillez uploader une image {{ expected }}";
					
					throw new ImageFieldValidatorException($msg,[
						"expected"=>"carré ou paysage",
						"given"=>"portrait",
						"filename"=>$filename,
					]);
				}
			}

			if($this->options['allowLandscape'] === false){
				if($width > $height){
					$msg = "l'image '{{ filename }}' est en paysage, veuillez uploader une image {{ expected }}";
					
					throw new ImageFieldValidatorException($msg,[
						"expected"=>"portrait ou carré",
						"given"=>"paysage",
						"filename"=>$filename,
					]);
				}
			}

			if($this->options['mimeType']){
				if(is_string($this->options['mimeType'])) {
					if(strtolower($this->options['mimeType']) != "image/*"){
						if(strtolower($this->options['mimeType']) != strtolower($mime)){
							$msg = "l'image '{{ filename }}' est de type '{{ given }}', seul les images de type {{ expected }} sont acceptées";

							throw new ImageFieldValidatorException($msg,[
								"expected"=>$this->options['mimeType'],
								"given"=>$mime,
								"filename"=>$filename,
							]);
						}
					}
				}
				else if(is_array($this->options['mimeType'])) {
					if(!in_array(strtolower($mime), $this->options['mimeType'])) {
						$msg = "l'image '{{ filename }}' est de type '{{ given }}',seul les images de types {{ expected }} sont acceptées";

						throw new ImageFieldValidatorException($msg,[
							"expected"=>implode(", ", $this->options['mimeType']),
							"given"=>$mime,
							"filename"=>$filename,
						]);
					}
				}
			}

			return true;
		} catch (Exception $e) {
			
		}
		catch(ImageFieldValidatorException $e){
			$msg = $e->getMessage();
			$cell = $this->getOption('cellToProcess');
			$msg .= " dans la cellule $cell";
			return $msg;
		}
		return false;
	}
}