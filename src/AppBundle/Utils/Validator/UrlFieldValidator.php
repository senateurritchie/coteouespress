<?php
namespace AppBundle\Utils\Validator;
use AppBundle\Utils\Validator\FieldValidator;

class UrlFieldValidator extends FieldValidator{
	/**
	* le domain de l'url
	* @var string
	*/
	protected $host;
	/**
	* le protocol de l'url
	* @var string
	*/
	protected $scheme;
	/**
	* le port de l'url
	* @var string
	*/
	protected $port;

	public function __construct($field,$host = null,$scheme = null,$port = null){
		parent::__construct($field);
		$this->host = $host;
		$this->scheme = $scheme;
		$this->port = $port;
	}

	public function validate($value){
		if(($var = filter_var($value,FILTER_VALIDATE_URL))) {
			if($this->scheme){
				if($var['scheme'] != $this->scheme) return false;
			}

			if($this->host){
				if($var['host'] != $this->host) return false;
			}

			if($this->port){
				if($var['port'] != $this->port) return false;
			}

			return true;
		}
		return false;
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
}