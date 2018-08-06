<?php	
namespace AppBundle\Utils\Event;

/**
* evenement
*
* @package
* @author Zacharie Aké Assagou <zakeszako@yahoo.fr>
* @version 1.0
*/
class Event{
	protected $name;
	protected $value;
	protected $propagationStopped;

	function __construct($name=null,$value=null){
		$this->propagationStopped = false;
		$this->setName($name);
		$this->setValue($value);
	}

	public function setName($name){
		$this->name = $name;
		return $this;
	}
	public function setValue($value){
		$this->value = $value;
		return $this;
	}
	public function stopPropagation(){
		$this->propagationStopped = true;
		return $this;
	}


	public function getName(){
		return $this->name;
	}
	public function getValue(){
		return $this->value;
	}
	public function isPropagationStopped(){
		return $this->propagationStopped;
	}
}