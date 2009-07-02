<?php
/**
 * Limit Depth RecursiveIteratorIterator class
 *
 * @see http://no.php.net/manual/en/ref.spl.php
 */
class LimitRecursiveIteratorIterator extends RecursiveIteratorIterator 
{
    protected $depth_limit;
    
    /**
     * No depth limit by default
     *
    **/
    //public function __construct (Iterator $it, $mode = RIT_SELF_FIRST, $depth_limit = -1)
    public function __construct (RecursiveIterator $it, $mode = RIT_SELF_FIRST, $depth_limit = -1)
    {
        parent::__construct($it, $mode);
        $this->depth_limit = $depth_limit;
    }
    
    
    /**
     * After the call to next() if depth is bigger than limit then
     * just skip all subIterators for that depth until depth end.
     *
    **/
    public function next ()
    {
        parent::next();

        if ($this->getDepth() == $this->depth_limit)
        {
            while ($this->getSubIterator()->valid())
                $this->getSubIterator()->next();
            parent::next();
        }
    }
    
    
    
    
    /**
    * @return boolean Checks if the entry is legal (true) or ignored (false)
    */
    /*
    private function legalEntry()
    {
        $it = $this->getSubIterator();
        $path = $it->current()->getPathName();
        
        $sub4path = substr($path,-4); // 4 last characters
        
        $valid = 
            $it->valid() && 
            is_dir($path) && // is a directory
            !(  // not .svn or .cvs
                $sub4path=='.svn' ||
                $sub4path=='.cvs'
            );
        return $valid;
    }
    */
}

?>