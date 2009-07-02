<?php
$i = $w->getFileIterator(); 
for($i as $f){
    echo '<div>'.$f.'</div>';
}
// Prints all files and folders recursively in the widget folder
?>