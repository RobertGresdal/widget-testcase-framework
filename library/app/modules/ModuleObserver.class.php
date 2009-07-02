<?php
/**
 * Will listen on all events from all modules. Other modules 
 * must ask this class for events on another module, so this
 * class will be the binding observer between them. This class 
 * is a singleton. 
 * 
 * @author Robert Græsdal
 */
class ModuleObserver implements Singleton {
	static private $instance;
	private $moduleList = array();
	
	private function __construct(){} // 

	/**
	 * Gets the instance of this singleton. 
	 * @return ModuleObserver singleton
	 */
	public static function getInstance(){
		if( ! $this->instance instanceof ModuleObserver ){ 
			$this->instance = new ModuleObserver();
		}
		return $this->instance;
	}
	
	/**
	 * Register yourself as a listener on a module event
	 * 
	 * @param EventListener $listener 
	 * @param string $moduleName
	 * @param string $eventname
	 * @return void
	 */
	public function listen(EventListener $listener, $moduleName, $eventname){
		// Add listener to a list where we can loop up modulename and eventname.
		// So hash list where modulename is key, value is another hash list
		// where eventname is key, value is array of listeners
		$this->$moduleName[$eventName][] = $listener;
	}
	
	/**
	 * Forwards an event to a listener
	 * @param Event $event
	 * @return void
	 */
	private function callEvent(Event $event){
		$listeners = $this->moduleName[$eventName];
		foreach( $listeners as $listener ){
			$listener->callEvent($event);
		}
	}
}
?>