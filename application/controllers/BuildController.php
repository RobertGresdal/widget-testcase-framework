<?php

/**
 * ListController is the controller for showing a list of all testcases.
 */
class BuildController extends Zend_Controller_Action
{
    private $_builder;
    
    /**
    * Return the zip file and use the widget mimetype
    * @todo P2 write the action
    */
    public function installAction(){
        // Disable layout
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        
        // Build the zipfile
        $this->_build();
        
        // Print the contents of the file
        $file = $this->_builder->getFilename();
        if ($stream = fopen($file, 'r')) {
            header('Content-type: application/x-opera-widgets');
            
            // print all the page starting at the offset 10
            echo stream_get_contents($stream);
            fclose($stream);
        }else{
            header('Content-type: text/plain');
            echo 'Unable to open file';
        }
    }
    
    /**
    * Return the zip file and use the zip mimetype
    */
    public function downloadAction(){
        // Disable layout
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        
        // Build the zipfile
        $this->_build();
        
        // Print the contents of the file
        $file = $this->_builder->getFilename();
        if ($stream = fopen($file, 'r')) {
            //header('Content-type: text/plain');
            header('Content-type: application/zip');
            
            // print all the page starting at the offset 10
            echo stream_get_contents($stream);
            fclose($stream);
        }else{
            echo 'Unable to open file';
        }
    }
    
    /**
    * Build the zip and store the handle pointing to the file
    * so the downloadAction and installAction can use it.
    */
    private function _build(){
        $registry = Zend_Registry::getInstance();
        $path = $registry->configuration->testcase->dir.'/'.$this->getRequest()->getParam(2);
        $this->_builder = new WidgetBuilder(new WidgetTC($path));
    }
    
    /**
    * Return a 406 Not Acceptable code
    * @todo uncomment the throw and write the builderexception
    */
    private function _error(){
        /// @todo: set a 406 error code
        //throw new BuilderException('Requested path does not contain a testcase.');
    }
}