<?php

/**
* Takes a string with folders and subfolders and makes every part of it 
* into a link. such that foo/bar/file.txt outputs 
* <a href="foo">foo</a>/<a href="foo/bar">bar</a>/<a href="foo/bar/file.txt">file.txt</a>
*/
function smarty_modifier_folder_links($subject,$pre) //$subject, $stripper){
{ 
    $subject = strtr($subject,'\\','/'); // make sure we have forward slashes
    $parts = explode('/',$subject);
    $distance=array();
    $result = array();
    foreach($parts as $folder){
        $distance[] = $folder;
        $link = implode('/',$distance);
        $result[] = "<a href=\"$pre$link\">$folder</a>";
    }
    return implode('/',$result);
}

?>