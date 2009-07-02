<?php

/**
* Wrapper class for the Zend_Log module. This used to be a
* wrapper for Pear Log, but we found it better to go for
* the Zend classes when we first went for a framework.
* 
* @example Log::log('Message', Log::NOTICE);
*/
class Log {
	const EMERGENCY	= Zend_Log::EMERG;
	const ALERT		= Zend_Log::ALERT;
	const CRITICAL	= Zend_Log::CRIT;
    const ERROR		= Zend_Log::ERR;
    const WARNING	= Zend_Log::WARN;
    const NOTICE	= Zend_Log::NOTICE; 
    const INFO		= Zend_Log::INFO;
    const DEBUG		= Zend_Log::DEBUG;
    
	private function __construct(){
	}
	
    //static private $handler = 'console';
    //static private $name = '';
    //static public $ident = 'TEST';
    //static private $conf = array();
    //static private $maxLevel = PEAR_LOG_DEBUG();
    
    /**
    * @todo P3 - Don't make a new instance of the writer and log every time
    */
    public static function log($msg, $flag){
    	// TODO get the logging priority and output writer from config
    	// TODO keep an eye on the log files, perhaps delete files older than X days?
    	$filename = date('Ymd').'.txt';
    	$writer = new Zend_Log_Writer_Stream(
    		APPLICATION_PATH.'/../data/log/'.$filename
    	);
    	$log = new Zend_Log($writer);
    	$log->log($msg, $flag);
    	return true;
    }
}