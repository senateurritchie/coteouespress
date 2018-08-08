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
	/**
	 * le nom de l'évenement
	 * @var string
	 */
	protected $name;
	/**
	 * la donnée associé à lévenement
	 * @var mixed
	 */
	protected $value;
	/**
	 * l'entité qui a émis l'évenement
	 * @var \AppBundle\Utils\EventDispatcher
	 */
	protected $target;
	/**
	 * l'entité qui a émis l'évenement à l'origine
	 * 
	 * @var \AppBundle\Utils\EventDispatcher
	 */
	protected $relatedTarget;
	/**
	 * determine état de propagation de l'évenement
	 * @var boolean
	 */
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
	public function setTarget(\AppBundle\Utils\EventDispatcher &$dispatcher){
		$this->target = $dispatcher;
		return $this;
	}
	public function setRelatedTarget(\AppBundle\Utils\EventDispatcher &$dispatcher){
		$this->relatedTarget = $dispatcher;
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
	public function getTarget(){
		return $this->target;
	}
	public function getRelatedTarget(){
		return $this->relatedTarget;
	}
}