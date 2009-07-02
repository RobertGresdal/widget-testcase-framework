<?php

class WidgetConfigBuilder {
    private $_zendview;
    private $_testcase;
    private $_appConfig;
    
    public function __construct( WidgetTC $wtc ){
        $this->_zendview = new Zend_View_Smarty();
        /// @todo: throw exception here when wtc is not valid or handle it in the widgettc itself when asking for config, or rather just create a default config and log as warning?
        $this->_testcase = $wtc;
        
        $reg = Zend_Registry::getInstance();
        $this->_appConfig = $reg->configuration;
    }
    
    /*public function __get($var){
        $value = null;
        switch($var){
            case 'config_1_0':
                $value = $this->_build('1.0');
                break;
        }
        return $value;
    }*/
    
    /*
    * Builds the config and returns as string formatted as xml
    */
    public function build($version){
        if( $version != '1.0' ){
            throw new Exception('Version unknown. Supported: "1.0"');
        }
    
        $view = $this->_zendview;
        $view->testcase = $this->_testcase;
        $view->base_tc_path = realpath($this->_appConfig->testcase->dir);
        
        $template = 'builder/config_xml_1_0.tpl';
        $cache_id = 0;
        return $view->render($template, $cache_id);
    }
}

?>