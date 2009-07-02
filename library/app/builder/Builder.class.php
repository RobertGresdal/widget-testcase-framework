<?php
/**
* @package library
*/


/**
* Builds a downloadable archive. Extend this class to make a specific
* builder, such as a WidgetBuilder.
* 
* 
*/
abstract class Builder {
    /** Where the builder should save its file */
    private $path = null;
    private $zip = null;
    private $flags = 0;
    
    //const CREATE=ZIPARCHIVE::CREATE;
    //const OVERWRITE=ZIPARCHIVE::OVERWRITE;
    
    /**
    * @param string $target_path The path of the zip file we're saving to
    * @param int $flags Flags for how to create the file. Takes the same flags as ZipArchive::open()
    * @see http://no.php.net/manual/en/function.ziparchive-open.php
    */
    public function __construct($target_path, $flags=ZIPARCHIVE::CREATE) {
        $this->flags = $flags;
        $this->path = realpath($target_path);
        $this->zip = new ZipArchive();
    }
    
    public function addDir($from_path, $internal_path=null){
        if( !realpath($from_path) ){
            throw new Exception('Directory "'.$from_path.'" does not exist.');
        }
    
        // Add '/' to the start of internal path if it is specified
        if(!is_null($internal_path) && strpos('/',$internal_path)!==0){
            $internal_path='/'.$internal_path;
        }
        
        // Open archive so we can write to it
        $this->open();
        
        // make a recursive directoryiterator
        $iterator  = new RecursiveIteratorIterator(new FilenameFilterIterator($from_path,array('.svn')));
        // iterate over a recursive directoryiterator and 
        // add each file found to the archive
        foreach ($iterator as $key=>$value) {
            /** FIXME what happens when someone forgets the '/' at the end of internal_path there? 
                 maybe we should add realpath() around more of this stuff */
            $inside_path = substr($key,strlen($from_path)+1);
            if( $this->zip->addFile(realpath($key), $internal_path.$inside_path) !== true ){
                throw new Exception("Unable to add file: $key");
            }//or die ("ERROR: Could not add file: $key");        
        }
        
        $this->close();
    }
    
    public function addFile($from_filename, $internal_filename=null){
        $this->open();
        if(is_null($internal_filename)){
            return $this->zip->addFile(realpath($from_filename));
        } else {
            return $this->zip->addFile(realpath($from_filename), $internal_filename);
        }
        $this->close();
    }
    
    public function addFromString($internal_filename, $string){
        $this->open();
        $res = $this->zip->addFromstring($internal_filename, $string);
        $this->close();
        return $res;
    }
    
    /**
    * Returns the archive we built
    * @return ZipArchive
    */
    public function getArchive(){
        return $this->zip;
    }
    
    public function getFilename(){
        return $this->path;
    }
    
    private function open(){
        if( ($error = $this->zip->open($this->path, $this->flags)) !== true ){
            throw new Exception("Unable to open zipfile \"{$this->path}\". ZipArchive->open returned Error #{$error}: ");
        }
        // if we leave the flag as overwrite or create, we cannot add more files
        // since all old files will be delted!
        // Instead, we now change the flag so it will do checks every time
        $this->flags = ZIPARCHIVE::CHECKCONS;
    }
    private function close(){
        $this->zip->close();
    }
}

?>
