<?
/**
* EventHandler classes can use so they don't have to implement the logic themselves,
* just pass forward the addEventListener, removeEventListener and callEvent functions
* to this class.
*/
class EventHandler {
    protected $_listeners;
	
    function __construct(){
    	$this->_listeners = array();
    }
    
    /**
    * Calls all listeners on the specific eventName
    * @param string $eventName - Name of event
    * @param Event $eventObject - Event object to pass to listeners
    */
    public function callEvent($eventName, Event $eventObject){
        /** @TODO call all listeners and pass the event object to them */
        $listeners = $this->_listeners[$eventName];
        foreach($listener as $listeners){
            call_user_function_array(
                array($listener->method,$listener->function), 
                $eventObject
            );
        }
    }
    
    /** 
    * @param string $eventname - Name of event, 'onview', 'onlist', etc.
    * @param mixed $callback - Function to call back, or an array with class- and function name
    * @return int eventID - Call removeEventListener(id) to delete it
    */
    public function addEventListener($eventName, $callback){
        $el = new EventListener($eventName,$callback);
        array_push($this->_listeners[$eventName], $el);
        return $el;
    }
}
