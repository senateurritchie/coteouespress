<?php	
namespace AppBundle\Utils;

use AppBundle\Utils\Event\Event;

/**
* gestionnaire d'evenements
*
* @package
* @author Zacharie Aké Assagou <zakeszako@yahoo.fr>
* @version 1.0
*/
class EventDispatcher{
	protected $data = array();

	function __construct(){
		$p = new \ReflectionClass($this);

		foreach ($p->getMethods() as $key => $method) {
			$name = $method->getName();
			if(preg_match("#^on_(.+)#i", $name,$m)){
				$func2 = function($value) use($name){
					return $this->$name($value);
				};
				$event = preg_replace("#_#i", ".", $m[1]);
				$this->subscribe($event,$func2);
			}
		}
	}

	/**
	* @param callable $listener
	*/
	public function hasListerner(callable $listener){
		foreach ($this->data as $key=>$observers) {
			foreach ($observers as $observer) {
				if ($observer === $listener){
					return true;
				}
			}
		}
	}

	/**
	* @param string $event
	*/
	public function hasEvent($event){
		return isset($this->data[$event]);
	}

	/**
	* @param string $event
	* @param callable $observer
	*/
	public function on($event,callable $observer){
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}
		$this->data[$event] = [$observer];
		return $this;
	}


	/**
	* @param string|Event $event
	* @param mixed $value
	*/
	public function emit($event,$value = null){

		if(is_string($event) || $event instanceof Event);
		else{
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}

		$evt_name = null;

		if(is_string($event)) {
			$evt_name = $event;

			$event = new Event();
			$event->setName($evt_name);
			$event->setValue($value);
		}
		else{
			$evt_name = $event->getName();
		}

		if(!isset($this->data[$evt_name])) return;
		$observers = $this->data[$evt_name];

		foreach ($observers as $observer) {
			call_user_func($observer,$event);
			if($event->isPropagationStopped()) break;
		}
		return $this;
	}
}