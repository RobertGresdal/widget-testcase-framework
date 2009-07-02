<?php

/**
* Strips the start of the subject based on the length
* of the second string. Useful for stripping away the
* first part of a path based on a base path.
* If you had, for instance, "/foo/bar/baz.txt" and
* you want to remove the initial "/foo/", call this 
* function with strip_start($fullpath, '/foo/')
*/
function smarty_modifier_option_boolean($subject)
{ 
    $value=null;
    if( $subject ){
        $value = '<image src="icons/tick.png" title="Enabled" alt="Enabled" />';
    } else {
        $value = '<image src="icons/cross.png" title="Disabled" alt="Disabled" />';
    }
    
    return $value;
}

?>