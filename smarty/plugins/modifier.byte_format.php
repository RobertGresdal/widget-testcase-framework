<?php

/**
* Formats a number as bytes, kilobytes etc.
* @TODO *do* the formatting instead of just adding a thousands separator.
*/
function smarty_modifier_byte_format($subject) 
{
    $kb = 1024;
    $mb = 1048576;
    $gb = 1073741824;
          //4294967296
    if( $subject >= $gb )
        return number_format($subject/$gb, 2, '.', ' ').'G';
    elseif( $subject >= $mb )
        return number_format($subject/$mb, 2, '.', ' ').'M';
    elseif( $subject >= $kb )
        return number_format($subject/$kb, 2, '.', ' ').'K';
    else
        return $subject;
}

?>