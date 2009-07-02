<?php
/**
 * Ignore certain file and dirnames
 *
 * @see http://no.php.net/manual/en/ref.spl.php
 * 
 */
class FilenameFilterIterator extends FilterIterator implements RecursiveIterator
{
    private $_ignore_path;
    private $_ignore_file;
    
    /**
    * @param array $ignore An array of all files and paths to ignore
    */
    public function __construct ($path, $ignore_path=null, $ignore_file=null)
    {
        parent::__construct(new RecursiveDirectoryIterator($path));
        $this->_ignore_path = $ignore_path;
        $this->_ignore_file = $ignore_file;
    }

    /**
    * Function to decide whether an element of the inner iterator 
    * should be accessible through the Filteriterator.
    * @todo Log a debug message, trying to accept $path
    */
    public function accept()
    {
        $path = $this->current()->getPathName();
        $accept = true;
        $log = '';
        
        // if we find any ignorable parts, accept false and jump to end
        if( is_array($this->_ignore_file) && $this->current()->isFile() ) {
            $filename = $this->current()->getFilename();
            if( array_search($filename,$this->_ignore_file) !== false ){
                $accept = false;
                //$log = get_class($this).'->accept() ignoring file "'.$path.'".';
            }
        } else if( is_array($this->_ignore_path) ) {
            preg_match('/([^\/]+)$/',strtr($path,'\\','/'),$match);
            $folder = $match[0];// get the last part of the dirname
            if( array_search($folder,$this->_ignore_path) !== false ){
                $accept = false;
                //$log = get_class($this).'->accept() ignoring dir "'.$path.'".';
            }
        }
                
        //Log::log($log,Log::DEBUG);
        return $accept;
    }
    
    public function hasChildren ()
    {
        return $this->getInnerIterator()->hasChildren();
    }
    
    public function getChildren ()
    {
        return new self($this->getInnerIterator()->getPathname(), $this->flags);
    }
}
?>