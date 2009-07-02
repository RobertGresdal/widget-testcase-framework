<?
/**
*
*/
class EventListener {
	/** Name of event
	* readonly */
    private $_eventName = null;
	/** String or Array with name of function or class+method
	* readonly */
    private $_callback = null;
    
    function __construct($eventName, $callback){
        $this->_eventName = $eventName;
        $this->_callback = $callback;
    }
    
	public function __get($name){
		switch($name) {
			case 'eventName':
				return $this->_eventName;
			case 'callback':
				return $this->_callback;
			default:
				return null;
		}
	}
}
