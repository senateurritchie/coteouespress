<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class UrlFieldValidator extends FieldValidator{
	
	protected $default_options = array(
		"scheme"=>null,
		"host"=>null,
		"port"=>null,
	);

	public function __construct($field,array $options = array()){
		parent::__construct($field,array_merge($this->default_options,$options));
	}

	public function validate($value){

		if($this->getOption("multiple")){
			$urls = preg_split("#[;,]#", $value);

			$urls = array_map(function($el){
				return strip_tags(trim($el));
			}, $urls);

			$urls = array_filter($urls,function($el){
				return trim($el);
			});

			foreach ($urls as $key => $url) {
				if(true !== ($ret = $this->check($url))) {
					return $ret;
				}
			}

			$urls = array_map(function($el){
				return $this->processFilters($el);
			}, $urls);

			$this->emit('validated',$urls);
		}
		else{
			if(true !== ($ret = $this->check($value))) {
				return $ret;
			}

			$this->emit('validated',$value);
		}
		return true;
	}

	public function getHost(){
		return $this->host;
	}

	public function getScheme(){
		return $this->scheme;
	}

	public function getPort(){
		return $this->port;
	}

	/**
	 * test si l'élement en parametre est un url valide
	 * qui reponds à certains craitères
	 * 
	 * @param  string $value l'url à tester
	 * @return mixed
	 */
	public function check($value){
		$cell = $this->getOption('cellToProcess');

		if(($var = filter_var($value,FILTER_VALIDATE_URL))) {

			$var = parse_url($var);

			if(($data = $this->getOption('scheme'))){
				if($var['scheme'] != $data){
					$scheme = $var['scheme'];
					return "[$this->mappedBy]: '$scheme' n'est pas un protocol valide. le protocol '$data' doit être utilisé. cellule $cell";
				}
			}

			if(($data = $this->getOption('host'))){
				if($var['host'] != $data){
					$host = $var['host'];
					return "[$this->mappedBy]: '$host' n'est pas un domaine valide. le domaine '$data' doit être utilisé. cellule $cell";
				}
			}

			if(($data = $this->getOption('port'))){
				if($var['port'] != $data){
					$port = $var['port'];
					return "[$this->mappedBy]: '$port' n'est pas un port valide. le port '$data' doit être utilisé. cellule $cell";
				}
			}

			return true;
		}
		return "[$this->mappedBy]: '$value' n'est pas une url valide. cellule $cell";
	}
}