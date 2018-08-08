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
	private $__data__ = array();

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
	* Retourne les listeners liés à un evenement
	* 
	* @param string $event
	*/
	public function getEventListeners($event){
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}

		return isset($this->__data__[$event]) ? $this->__data__[$event] : [];
	}
	/**
	* Remplace les listeners existants par une nouvelle liste
	* 
	* @param string $event
	* @param array $listeners
	*/
	public function setEventListeners($event,&$listeners){
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}

		$this->__data__[$event] = $listeners;
		return $this;
	}
	

	/**
	* @param callable $listener
	*/
	public function hasListerner(callable $listener){
		foreach ($this->__data__ as $key=>$observers) {
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
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}
		return isset($this->__data__[$event]);
	}

	/**
	* @param string $event
	* @param callable $observer
	*/
	public function on($event,callable $observer){
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}

		if(count($this->getEventListeners($event))) {
			$this->__data__[$event][] = $observer;
		}
		else{
			$this->__data__[$event] = [$observer];
		}
		return $this;
	}

	/**
	* Supprime tout evenement du nom '$event'
	* Si $observer est fourni alors seul ce listener sera supprimé
	* 
	* @param string $event
	* @param callable $observer
	*/
	public function off($event,callable $observer = null){
		if(!is_string($event)){
			throw new InvalidArgumentException("'event' argument type must be 'string', '".gettype($event)."' was given");
		}

		// suppresion de tout les listeners de cet evenement
		if($observer == null){
			$this->setEventListeners($event,[]);
		}
		// suppresion d'un listener specifique
		else{
			$listeners = $this->getEventListeners($event);
			$listeners = array_filter($listeners,function($el)use(&$observer){
				return ($el !== $observer);
			});
			$this->setEventListeners($event,$listeners);
		}

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

		if(!isset($this->__data__[$evt_name])) return;
		$observers = $this->__data__[$evt_name];

		$event->setTarget($this);

		foreach ($observers as $observer) {

			call_user_func($observer,$event);
			if($event->isPropagationStopped()) break;
		}
		return $this;
	}

	/**
	* Propage un événement sans affecter
	* en gardant une trace emeteur à l'origine.
	* 
	* @param Event $event
	*/
	public function propagate($event){
		if(!($event instanceof Event)){
			throw new InvalidArgumentException("'event' argument instance must be 'AppBundle\Utils\Event\Event', '".gettype($event)."' type was given");
		}

		$dispatcher = $event->getTarget();
		$event->setRelatedTarget($dispatcher);

		$this->emit($event);
		return $this;
	}
}