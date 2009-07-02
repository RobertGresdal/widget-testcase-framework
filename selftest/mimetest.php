<?php
/*$file = realpath('C:\\xampp\\htdocs\\apache_pb2.gif');
echo $file.' mime is "'.mime_content_type($file).'"';*/

$filename = realpath('class_vars_for_RDI.php');
$filename = realpath('../../apache_pb2.gif');
$mimefile = 'C:\\xampp\\htdocs\\wtf2\\library\\mime\\magic';

/*
$handle = finfo_open(FILEINFO_MIME,'C:/xampp/php/extras/magic');
var_dump($handle);
$mime_type = finfo_file($handle,$file); 
echo $mime_type;
*/
/*
$finfo = new finfo(FILEINFO_MIME, $mimefile); 

if (!$finfo) {
    echo "Opening fileinfo database failed";
    exit();
}

// get mime-type for a specific file 
echo $finfo->file($filename);
*/

require_once('C:\\xampp\\htdocs\\wtf2\\library\\mime\\MimeMagic.class.php');
$mm = new MimeMagic();
echo $mm->get($filename);
?>