<?php
/**
* @package wtf2
*/

/**
* Builds a new archive containing a widget testcase.
* 
* Depends on Zend_Config
* @see Zend_Config
*/
class WidgetBuilder extends Builder 
{
    private $testcase;
    private $appConfig;
    
    /** Default cache directory is within the 'tmp' folder of the current working directory */
    const DEFAULT_CACHEDIR = 'tmp';
    /** Default include directory is within the 'wtflib' folder of the current working directory */
    const DEFAULT_INCLUDEDIR = 'wtflib';
    /** Default testcase directory is within the 'tc' folder of the current working directory */
    const DEFAULT_TESTCASEDIR = 'tc';
    
    /**
    * @param WidgetTC $wtc A widget testcase object
    * @param Zend_Config $config Takes configuration for building the widget
    */
    public function __construct($wtc){
        $this->testcase = $wtc;

        // Fetch the configuration
        if( class_exists('ZEND_REGISTRY') ){
            $reg = Zend_Registry::getInstance();
            $this->appConfig = $reg->configuration;
        } else {
            // If Zend_Registry is not defined, use defaults so the 
            // class can work without having defined a registry
            $this->appConfig = new stdClass;
            /// @todo: wouldn't dirname(__FILE__) work better instead of getcwd?
            $this->appConfig->testcase->cache_dir    = getcwd().'/'.self::DEFAULT_CACHEDIR;
            $this->appConfig->testcase->include_dir  = getcwd().'/'.self::DEFAULT_INCLUDEDIR;
            $this->appConfig->testcase->dir = getcwd().'/'.self::DEFAULT_TESTCASEDIR;
        }
        
        $filename = tempnam($this->appConfig->cacheDir, date('Ymd').'-'); // filename example: 20081231-...
        parent::__construct($filename, ZIPARCHIVE::OVERWRITE); 

//echo $this->appConfig->testcaseDir.'/'.$this->testcase->path;
//echo isset($this->appConfig->testcaseDir)?'yes':'no';

        $this->build();
    }
    
    /**
    * Does the actual building
    */
    private function build(){
        /**
        * @todo most prespeficied files here will trickle up from the wtf.ini file,
        * being overwritten by any widget.ini files - but the standard library is 
        * a special case in that the file itself will be changeable. The same is true
        * for config.xml file. I need a dynamic file builder, that is able to take
        * some string content and add on content from files - but should we do this
        * configurably? maybe just add it hardcoded in the start and later we can
        * change it. Yes... that seems like an ok idea.
        */
        
        // Add all standard include files
        if( isset($this->appConfig->testcase->include_dir) ){
            $this->addDir( realpath($this->appConfig->testcase->include_dir) );
        }
        
        // Add the config.xml file
        $wcb = new WidgetConfigBuilder($this->testcase);
        $this->addFromString( 'config.xml', $wcb->build('1.0') );
        
        /// Add the js library file
        $wjsb = new WidgetJSLibraryBuilder($this->testcase);
        $this->addFromString( 'testlib.js', $wjsb->build('1.0') );
        
        // Adds any directories specified in the widget configuration
        if( isset($this->testcase->config->includeDirList) ){
            foreach($this->testcase->config->includeDirList as $dir){
                $this->addDir( realpath($this->appConfig->testcase->include_dir.'/'.$dir) );
            }
        }
        
        // Adds any files specified in the widget configuration
        if( isset($this->testcase->config->includeFileList) ){
            foreach($this->testcase->config->includeFileList as $file){
                $this->addFile($this->appConfig->testcase->include_dir.'/'.$file, $file);
            }
        }
        
        // Add the files for the widget itself (overwrites other files)
        /** $todo uhm, without this the entire thing is invalid? */
        /*if( isset($this->appConfig->testcase->dir) ){
            $this->addDir($this->appConfig->testcase->dir.'/'.$this->testcase->path);
        }*/
        $this->addDir($this->testcase->path);
    }
}
