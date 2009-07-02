<?php
/**
 * Directories only filter iterator class
 *
 * Class has been modified from the original version so 
 * you can tell it to ignore subversion and cvs folders.
 *
 * @see http://no.php.net/manual/en/ref.spl.php
 * 
 */
class DirectoryOnlyIterator extends FilterIterator implements RecursiveIterator
{
    /** Ignores any .svn folders */
    const IGNORE_SVN = 1;
    /** Ignores any .cvs folders */
    const IGNORE_CVS = 2;
    
    private $flags;
    
    public function __construct ($path, $mode)
    {
        parent::__construct(new RecursiveDirectoryIterator($path));
        $this->flags = $mode;
    }

    /**
    * Function to decide whether an element of the inner iterator 
    * should be accessible through the Filteriterator.
    * @todo Log a debug message, trying to accept $path
    */
    public function accept()
    {
        $path = $this->current()->getPathName();
        $accept = false;
        $log = '';
        
        if( (self::IGNORE_SVN & $this->flags) && substr($path,-4)=='.svn' ){
            // If we ignore svn and this is .svn folder, return false
            $log = get_class($this).'->accept() on "'.$path.'". Ignoring ".svn" folder.';
        } else if( (self::IGNORE_CVS & $this->flags) && substr($path,-4)=='.cvs' ){
            // If we ignore cvs and this is .cvs folder, return false
            $log = get_class($this).'->accept() on "'.$path.'". Ignoring ".cvs" folder.';
        } else {
            $accept = $this->getInnerIterator()->hasChildren();
            $log = get_class($this).'->accept() on "'.$path.'", returns '.($accept?'true.':'false.');
        }
        
        Log::log($log,Log::DEBUG);
        return $accept;
    }
    
    /** 
    * Note, we can probably extend this class and modify this function
    * to check if the current is a WidgetTC directory, thereby returning
    * false. That will enable us to stop iterating inside a widget testcase
    * which will be nice in case the testcase contains lots of directories
    * and or any files which might fool us to think such a subfolder is a 
    * widget tc.
    */
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