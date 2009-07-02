<?php
/**
* @package wtf2
*/

/**
* Represents a Widget TestCase and provides functions to check
* if the path supplied does contain a widget testcase, methods
* to add files, get contents of files, ask for configurational 
* settings, etc.
*
* @todo add file method
* @todo get content of files
* @todo get configurational settings
*
* @package wtf2
*/
class WidgetTC /*implements Traversable*/ {
    /** (ID flag) Folder has no identifier file  */
    const DOES_NOT_EXIST = 0;
    /** (ID flag) Folder has config.xml file */
    const CONFIG_XML     = 1; 
    /** (ID flag) Folder has widget.txt */
    const WIDGET_TXT     = 2;
    
    
    
    /** 
    * Forces class to believe the path contains a widget 
    * even though an identifier file is missing.
    */
    const FORCE_WIDGET = 0x1;
    
    
    // String. Path of the widget
    private $path = null;
    // holds the sum of the ID flags after calling valid()
    private $_IDFileFlags = null;
    // hold the config object
    private $_config;
    
    
    /**
    * Constructor
    * 
    * @param string $path Path to a folder
    */
    public function __construct($path){
        if( is_string($path) ){
            $this->path = $path;
        } else {
            $this->path = null;
        }
    }
    
    
    /**
    * Validates a folder as a Widget testcase.
    * 
    * @uses if($w->valid() & WidgetTC::CONFIG_XML){hasconfigxml();}
    * @see DOES_NOT_EXIST
    * @see CONFIG_XML
    * @see WIDGET_TXT
    * 
    * @see _getIDFileFlags 
    * @return int A sum of flags that tells us all of the identifier files it found. 
    *   If both config.xml and widget.txt exist, the sum returned is 
    *   WidgetTC::CONFIG_XML + WidgetTC::WIDGET_TXT.
    */
    public function valid() {
        return $this->IDFileFlags;
    }
    
    
    /**
    * Gets you the files within the widget folder.
    *
    * Returns a RecursiveIteratorIterator that is iterating
    * over a RecursiveDirectoryIterator that is pointing to
    * the widget directory. If the path contains no widgets, 
    * an empty Iterator will be returned unless you give the
    * flag FORCE_WIDGET
    *
    * @example WidgetTC.getFileIterator.php Example shows how to print all files and folders in the widget folder recursively.
    * @todo selftest FORCE_WIDGET flag
    * @param int $flag FORCE_WIDGET forces the function to believe the path is a widget and returns files even though the folder is invalid regards to a widget testcase.
    * @return RecursiveIteratorIterator
    */
    public function getFileIterator($flag=null,$ignore_dir=null){
        if( $flag == self::FORCE_WIDGET || $this->valid() ){
            $i = new RecursiveIteratorIterator(
                new FilenameFilterIterator($this->path,$ignore_dir), 
                RecursiveIteratorIterator::SELF_FIRST
            );
        } else {
            // force widget was off and dir is not a valid widget testcase,
            // so we return an empty iterator.
            $i = new RecursiveIteratorIterator();
        }
        return $i;
    }
    
    public function __get($var){
        $value = null;
        switch($var){
            case 'config':
                //$value = new Zend_Config_Ini($this->path.'/widget.txt');
                if( is_null($this->_config) ){
                    $this->_config = $this->_getConfig();
                }
                $value = $this->_config;
                /*$value = new StdClass();
                $value->fileio = 'todo';
                
                */
                break;
            case 'path':
                $value = $this->path;
                break;
            case 'IDFileFlags':
                $value = $this->_getIDFileFlags();
                break;
        }
        return $value;
    }
    
    /**
    * Scans the testcase folder and finds which identifier files exist
    * @todo P3 - log the exception
    */
    private function _getIDFileFlags(){
        // Find the identifier files only once
        if( is_null($this->_IDFileFlags) ){
            $flag = 0;
            
            // does the path exist?
            if( $this->path != null ){
                try{
                    $di = new DirectoryIterator($this->path);
                    foreach($di as $f){
                        if( substr($f,-10)=='widget.txt' )
                            $flag += self::WIDGET_TXT;
                        else if( substr($f,-10)=='config.xml')
                            $flag += self::CONFIG_XML;
                    }
                } catch (RuntimeException $e) {
                    // Probably attempted to open a file and not a directory for new DirectoryIterator
                    /** @todo log the exception */
                    //$logger = &Log::singleton('console', '', 'test', $conf);
                    //$logger->log(getClass(self).' attempted to open a file as a directory.',PEAR_LOG_WARNING);
                    Log::log(get_class($this).' attempted to open a file as a directory.',Log::WARNING);
                }
            } else {
                /** $todo someone called valid on this object with an invalid path. log as a warning */
                Log::log('Cannot call valid() in '.get_class($this).' with an empty path.',Log::WARNING);
            }
            // Remember the flag
            $this->_IDFileFlags = $flag;
        }        
        
        return $this->_IDFileFlags; 
    }
    
    /**
    * Gets a configuration object for the widget
    */
    private function _getConfig(){
        $config = null;
        

        if ( $this->IDFileFlags & self::WIDGET_TXT ){
            // We use the old parser for the old ini format
            $file = $this->path.'/widget.txt';
            $inifile = file_get_contents($file);
            
            $config = new stdClass();
            $ini = WTF_BWCompat::parse_ini($inifile);
            
            $this->merge_old_ini($config, $ini);
            $config->comment = $this->_commentFromConfig($file);
        } else if ( $this->IDFileFlags & self::CONFIG_XML ){
            //echo 'using config';
        } else {
            throw new Exception('Widget has no configuration. Flags passed here: "'.$this->IDFileFlags.'".');
        }
        return $config;
    }
    
    /**
    * Takes a config object and adds in the values from the old ini
    * format. We only add the values we know should be there from
    * the old format.
    * 
    * @todo P5 move to static function in some kind of ini class
    */
    private function merge_old_ini($config, $old){
        /*if( isset($old['file']) ) $config->fileio = $old['file'];*/
        if( isset($old['fileio']) ) {
            $config->fileio = $this->_ini_to_boolean($old['fileio']);
        }
    }
    
    /**
    * Converts "yes", 1, "enabled" to boolean true, everything else to false
    * @todo P5 move to static function in some kind of ini class
    */
    private function _ini_to_boolean($setting){
        if ( $setting == 1 /* string or number, do not use "==="! */ ) {
            return true;
        } else if ( $setting=='yes' ){
            return true;
        } else if ( $setting=='enabled' ) {
            return true;
        }
        return false;
    }
    
    /**
    * Reads the comment from the old widget config files, which
    * are any lines from the start until there is a line with
    * an equal sign in it.
    * 
    * @todo P5 move to static function in some kind of ini class
    */
    private function _commentFromConfig($file){
        $comment = null;
        $lines = file($file);
        foreach($lines as $num => $line){
            if( strpos($line,'=')===false ){ 
                $comment .= $line;
            } else {
                // Stop at the first line containing an equals sign.
                break;
            }
        }
        return $comment;
    }
}

