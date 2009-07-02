<?php

/**
 * Loads all modules and calls register on them so they can get
 * a binding to ModuleObserver.
 * 
 * @author Robert Græsdal
 *
 */
class ModuleLoader {
	/** Stores module names */
	private $module = array();
	
	public static function autoload($class) {
        try {
            self::loadClass($class);
            return $class;
        } catch (Exception $e) {
            return false;
        }
    }
	
    
    public static function loadClass($class, $dirs = null){
		// make a recursive directoryiterator
		$config = Zend_Registry::getInstance()->configuration;
		
        $from_path = $config->webapp ? $config->webapp->modules ? 
        	realpath($config->webapp->modules->path) :  
        	null : null;
        if( $from_path ){
        	$i  = new RecursiveIteratorIterator(
				new FilenameFilterIterator(
					$from_path, array('.svn','.hc','.cvs')
				)
			);
	        // Scan the modules folder and attempt to find the class
	        Log::log("ModuleLoader ; Scanning for {$class} in directory \"$from_path\".",Log::DEBUG);
	        foreach ($i as $key=>$value) {
	            // If we try to find Foo module, look for ModuleFoo.class.php
	            $match = "Module{$class}.class.php";
	            if( substr($value,-strlen($match)) == $match  ){
	            	Log::log("ModuleLoader ; Found class {$class} in file \"$value\".",Log::INFO);
	            	include_once($key);
	            	break 1; // exit foreach
	            } else {
	            	Log::log("ModuleLoader ; Skipping file \"{$value}\".",Log::DEBUG);
	            }
	        }
	        return $class;
        } else {
        	return false;
        }
	}
	
	/*
	 * TODO will look in the modules path for a matching class or instance
	 * 
	 * /
	public static function autoload(){
		$registry = Zend_Registry::getInstance();
		$modulePath = realpath($registry->configuration->webapp->modules);

		//parent::loadClass($class, $modulePath);
		try {
            self::loadClass($class, $modulePath);
            return $class;
        } catch (Exception $e) {
            return false;
        }
	}/**/
	
	/**
	 * Register to listen for an event
	 * @return unknown_type
	 * /
	public function register($moduleName, $eventName){
		$id = spl_object_hash($moduleName);
		// Make sure entry is an array
		if( ! is_array($this->module[$id]) )$this->module[$id]=array();
		
		array_push( $this->module[$id], $eventName );
		/// TODO return true on success else false?  
	}/**/
	
	/**
	 * Returns an array containing a list of everyone that
	 * listened for the event in the specified module.
	 * 
	 * @param class $moduleName - name of module
	 * @param string $eventName 
	 * @return mixed - array of listeners or false if no were found
	 * /
	public function getListeners($moduleName, $eventName){
		$id = spl_object_hash($moduleName);
		if( is_array($this->module[$id]) ){ 
			return $this->module[ $id ]; 
		} else {
			return false;
		}
	}/**/
}