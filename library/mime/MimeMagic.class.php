<?php

class MimeMagic {
    protected $_finfo = null;
    
    public function __construct(){
        if( class_exists('finfo', false) ){
            $mimefile = realpath(dirname(__FILE__)).'/magic';
            $this->_finfo = new finfo(FILEINFO_MIME, $mimefile); 
        } else {
            Log::log("Fileinfo class not found, using fallback mime type detecting.",Log::NOTICE);
        }
    }
    
    /**
    * Get the mimetype of a specific file
    * 
    * The mime magic in php isn't very dependable, so this 
    * was added for only a tad bit of consistency. It's 
    * technically worse than the PHP built in function, 
    * but at least we can get the same result every time here.
    *
    * @todo P2 Write a unit test for this and check to see if 
    *    we are recieving the expected value
    * @todo P1 Don't use the magic file on linux?
    */ 
    public function get($filename){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            if( preg_match('/\.js$/', $filename) ){
                return 'text/javascript';
            } else if( preg_match('/\.txt$/', $filename) ){
                return 'text/plain';
            } else if( preg_match('/\.wgt$/', $filename) ){
            	/// FIXME check the spec to see what to do here
                return 'application/x-opera-widgets';
            } 
        }
        if( !is_null($this->_finfo) ){
            return $this->_finfo->file($filename);
        } else {
            return '';
        }
    }
}