<?php
/**
* Ternary modifier for smarty. Usage: {$value|ternary:'yes':'no'}
*/
function smarty_modifier_ternary($value,$option1,$option2)
{
    return ($value)?$option1:$option2;
}
?>