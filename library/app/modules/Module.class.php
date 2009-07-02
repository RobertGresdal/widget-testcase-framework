<?php
/**
* Modules must extend this class
* 
* FIXME as it is currently, we could make this an interface
*/
abstract class Module {
    private $eventHandler;
    
    public function __construct(){
    	$this->eventHandler = new EventHandler();
    }
    
    /** 
     * All modules must implement this function. They should
     * register which events they are interested on the 
     * ModuleObserver in this method. 
     */
    abstract public function initModule();
    
    /**
     * Calls all registered listeners and always call ModuleObserver 
     */
    protected function callEvent($eventName){
    	$this->eventHandler->callEvent(
    		new Event($this,$eventName)
    	);
    }
}