<?php
/**
* FIXME ... just fixme, like 99% of it. Count this as a draft or something. 
* 
*  
* 
* @package test
*
* Trying out File_Archive
*/
require_once('../../autoload.php');

try {
    Module::require('File_Archive');
} catch(Exception $e) {
    die('Script requires PEAR File_Archive to work.');
}


// Set cache dir for Cache Lite
$options = array('cacheDir'=>'tmp');

if( Module::isLoaded('Cache_Lite') ){
    File_Archive::setOption('cache',new Cache_Lite($options));
}
File_Archive::setOption('zipCompressionLevel',0);

// $files is an array of path to the files that must be added to the archive
File_Archive::extract(
    $files,
    File_Archive::toArchive(
        'myFiles.zip',
        File_Archive::toOutput()
    )
);

?>