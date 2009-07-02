<pre><?php
//set_include_path(get_include_path() . PATH_SEPARATOR . 'c:\\xampp\\htdocs\\wtf2\\');
require_once('../autoload.php');

$time_start = microtime(true);

try{
    $path = 'C:\\xampp\\htdocs\\wtf\\';
    
    $it = new LimitRecursiveIteratorIterator(
            new DirectoryOnlyIterator($path,
                DirectoryOnlyIterator::IGNORE_SVN |
                DirectoryOnlyIterator::IGNORE_CVS
                ), 
            RecursiveIteratorIterator::SELF_FIRST, 
            10
        );
    
    $n = 1;
    foreach ( $it as $e ){
        $w = new WidgetTC($e->getPathName());
        if($w->valid()){
            echo $n++.'. <b>'.$e->getPathName().'</b><br />';
        }
    }
}
catch(Exception $e){
    //echo 'No files found!<br/>';
    echo $e;
} 

$time_end = microtime(true);
echo '<br/><b>Script completed in '.($time_end-$time_start).'ms</b>';


?></pre>