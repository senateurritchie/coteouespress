<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;
use AppBundle\Utils\Validator\FieldValidatorManager;

class EntityFieldValidator extends FieldValidator{
	/**
	 * les options 
	 * @var array
	 */
	protected $default_options = array(
		"class"=>null,
		"entity_manager"=>null,
		"search_by"=>"slug",
		"multiple"=>false,
		"table_name"=>null,
	);

	public function __construct($field,$options){
		parent::__construct($field,array_merge($this->default_options,$options));
	}

	public function validate($value){
		$data = [];

		if($this->options['entity_manager'] && $this->options['class']) {
			$em = $this->options['entity_manager'];
			$ec = $this->options['class'];
			$rep = $em->getRepository($ec);
			$method = null;
			$values = null;


			if($this->getOption("multiple")){
				$values = explode(";", $value);

				$values = array_map(function($el){
					return strip_tags(trim($el));
				}, $values);

				$values = array_filter($values,function($el){
					return strip_tags(trim($el));
				});

				$slug = array_map(function($el){
					return $this->generateSlug($el);
				}, $values);


				$method = 'findBy'.ucfirst($this->options['search_by']);
			}
			else{
				$slug = $this->generateSlug($value);
				$method = 'findOneBy'.ucfirst($this->options['search_by']);
			}

			if(!($data = $rep->$method($slug))){
				if(count($values) > 1){
					$values = implode(", ", $values);

					return "[$this->mappedBy]: '$values' sont inconnus de la liste de ".$this->getOption('table_name');

				}

				return "[$this->mappedBy]: '$value' est inconnu de la liste de ".$this->getOption('table_name');
			}

			if(is_array($data)){

				foreach ($slug as $i=>$el) {
					$is_exists = false;
					foreach ($data as $el2) {
						if($el == $el2->getSlug()){
							$is_exists = true;
							break;
						}
					}

					if($is_exists === false){
						$missing = $values[$i];
						return "[$this->mappedBy]: '$missing' est inconnu de la liste de ".$this->getOption('table_name');
					}
				}
			}
			
			$this->emit('validated',$data);

			return true;
		}
		return false;
	}

	public function generateSlug($input,$sep = "-"){
   
        $input = transliterator_transliterate('Any-Latin;NFD;[:Nonspacing Mark:] Remove; Lower();',$input);

        $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');

        $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');

        $slug = str_replace($a, $b, $input);
        $slug = preg_replace("#[']+#","",trim($slug));
        $slug = preg_replace('#[^A-Za-z0-9]+#',$sep,trim($slug));
        $slug = preg_replace("#-+#", $sep, $slug);
        $slug = trim($slug,$sep);
        $slug = trim(strtolower($slug));
        return $slug;
	}
}