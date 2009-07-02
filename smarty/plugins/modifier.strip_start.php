<?php

/**
* Strips the start of the subject based on the length
* of the second string. Useful for stripping away the
* first part of a path based on a base path.
* If you had, for instance, "/foo/bar/baz.txt" and
* you want to remove the initial "/foo/", call this 
* function with strip_start($fullpath, '/foo/')
*/
function smarty_modifier_strip_start($subject, $strip) //$subject, $stripper){
{ 
    //return substr_replace($subject,'',0,length($stripper));
    if( is_string($strip) ){
        $length = strlen($strip);
    } else {
        $length = (int)$strip;
    }
    
    return substr_replace($subject,'',0,$length);
}

?>