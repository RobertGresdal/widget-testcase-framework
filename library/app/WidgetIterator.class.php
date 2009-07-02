<?php
/**
 * Takes an iterator and returns only those that is a valid widget testcase
 *
 * @see http://no.php.net/manual/en/ref.spl.php
 * 
 */
class WidgetIterator extends FilterIterator
{
    /** Ignores any .svn folders */
    //const IGNORE_SVN = 1;
    /** Ignores any .cvs folders */
    //const IGNORE_CVS = 2;
    
    //private $flags;
    
    public function __construct ($it)
    {
        parent::__construct($it);
        //$this->flags = $mode;
    }

    /**
    * Function to decide whether an element of the inner iterator 
    * should be accessible through the Filteriterator.
    * @todo Log a debug message, trying to accept $path
    */
    public function accept()
    {
        $path = $this->current()->getPathName();
        $w = new WidgetTC($path);
        $accept = $w->valid();
        
        //$log = '';
        
        //Log::log($log,Log::DEBUG);
        return $accept;
    }
}
?>