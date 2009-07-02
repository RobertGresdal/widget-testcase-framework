<?php
/**
* Builds a js library file accoding to the configuration
*/
class WidgetJSLibraryBuilder {
    //private $_zendview;
    private $_testcase;
    private $_appConfig;
    private $_reportConfig;
    
    public function __construct( WidgetTC $wtc ){
        //$this->_zendview = new Zend_View_Smarty();
        /// @todo: throw exception here when wtc is not valid or handle it in the widgettc itself when asking for config, or rather just create a default config and log as warning?
        $this->_testcase = $wtc;
        
        $reg = Zend_Registry::getInstance();
        $this->_appConfig = $reg->configuration;
        
        $this->_configureReporting();
    }
    
    /*
    * Builds the config and returns as string formatted as xml
    */
    public function build($version){
        if( $version != '1.0' ){
            throw new Exception('Version unknown. Supported: "1.0"');
        }
        
        $str = '';
        
        /** @todo P1 add the variable text (if requested) */
        if(false /**@todo P2 complete reporting back variable data*/){
            // call _configureReporting here
            $str_uri = ''; // get from config or request how to write the uri
            $str .= 'var REPORT_URI = "'.$str_uri."\"\r\n";
        }
        
        // Add the static data
        $special = realpath($this->_appConfig->testcase->special_include);
        if( $special !== false ){
            $str .= file_get_contents($special.'/testlib.js');
        } else {
            throw new Exception('Applications config must contain a valid path for testcase.special_inc. Current value is "'.$this->_appConfig->testcase->special_include.'".');
        }
        
        return $str;
    }
    
    private function _configureReporting(){
        /// @todo figure out how to set the report back uri. try to find in the
        ///    request, then look in the widget.txt, then in the app config
        ///    if no other config was set.
        //$this->_reportConfig = 
        if( isset($this->_appConfig->testcase->reporturi) ){
        
        } else if( $this->_testcase->config->reporturi ){
        
        } else if( false/* look in request */ ){
        
        }
    }
}