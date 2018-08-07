<?php	
namespace AppBundle\Utils\Exception;

class ImageFieldValidatorException extends \Exception{
	
	protected $options = array(
		"expected"=>null,
		"given"=>null,
		"filename"=>null,
	);

	function __construct($msg,array $options = array()){
		$this->options = array_merge($this->options,$options);
		parent::__construct($this->parse($msg));
	}

	public function parse($msg){
		return preg_replace_callback("#\{\{(.+?)\}\}#",function($p){
			$option = strip_tags(trim($p[1]));
			if(($v = $this->getOption($option))) {
				return $v;
			}
			return $p[0];
		}, $msg);
	}

	public function setOption($key,$value){
		$this->options[$key] = $value;
		return $this;
	}
	public function getOption($key,$default=null){
		return isset($this->options[$key]) ? $this->options[$key] : $default;
	}
	public function hasOption($key){
		return isset($this->options[$key]);
	}
}