<?php
require_once('../../autoload.php');


/*
$path = './';
$objects = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $path
    ), 
    RecursiveIteratorIterator::SELF_FIRST
);
foreach($objects as $name => $object){
    echo "$name <br>";
    //echo $object->getFilename().'<br>';
}
die();
*/


// Construct widget with all default settings
$widgetTC = 'foo/001';
$wb = new WidgetBuilder($widgetTC);
//die();
/*$f = $wb->getFilename();
echo $f."\n";
echo filesize($f)."<br><br>";
/**/

// ok zippingen virker. det some r problemet er at den starter
// helt med c: og tar med hele skiten! jeg må få den til å gi
// det interne navnet relativt til denne folderen.

// Stream the file to the client 
$f = $wb->getFilename();
header("Content-Type: application/zip"); 
header("Content-Length: " . filesize($f)); 
header("Content-Disposition: attachment; filename=\"a_zip_file.zip\""); 
readfile($f); 
//unlink($file);
/**/
?>