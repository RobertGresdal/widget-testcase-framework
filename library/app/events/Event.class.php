<?php
/**
* Contains information about which event happened. An object that supports events
* will create this object and pass it as an argument to the listener. The listener
* can then access information from the originating place/target/event with
* the $target value.
*/
class Event {
	/** References the object in which the event originated from ($this)
	* readonly */
	private $_target;
	/** Name of the event, as in 'load', 'beforeRender', etc. 
	* readonly */
	private $_name;
	
	public function __construct($target, $name){
		$this->target = $target;
		$this->name = $name;
	}
	
	public function __get($name){
		switch($name) {
			case 'name':
				return $this->_name;
			case 'target':
				return $this->_target;
			default:
				return null;
		}
	}
}