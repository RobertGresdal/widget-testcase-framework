<?php
/**
* Removes the byte order mark for UTF strings
*/
function smarty_modifier_noBOM($str)
{
    if( ord(substr($str,0,1))==0xEF && 
        ord(substr($str,1,1))==0xBB && 
        ord(substr($str,2,1))==0xBF ){
        $str = substr($str,3);
    }
    
    return $str;
}
?>