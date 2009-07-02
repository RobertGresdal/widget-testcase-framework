<pre><?php
require_once('autoload.php');

$time_start = microtime(true);

try{
    /*
    $w = new WidgetTC("tests/a/1");
    echo 'Flag: '.$w->valid()."\r\r";
    $iterator = $w->getFileIterator();
    foreach ($iterator as $file => $info) {
        echo $file.'<br/>';
    }
    */
    
    //$path = './';
    //$path = './tests/';
    $path = 'C:\\xampp\\htdocs\\wtf\\';
    /*$i = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path, 
                RecursiveDirectoryIterator::KEY_AS_PATHNAME &
                RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            ), 
            RecursiveIteratorIterator::SELF_FIRST
            //RecursiveIteratorIterator::CHILD_FIRST
            //RecursiveIteratorIterator::LEAVES_ONLY
        );/**/
    /*$i = new RecursiveIteratorIterator(
            new LimitedRecursiveDirectoryIterator(
                $path, 
                RecursiveDirectoryIterator::KEY_AS_PATHNAME &
                RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            ), 
            RecursiveIteratorIterator::SELF_FIRST
            //RecursiveIteratorIterator::CHILD_FIRST
            //RecursiveIteratorIterator::LEAVES_ONLY
        );/**/
    /*$i = new LimitedRecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path, 
                RecursiveDirectoryIterator::KEY_AS_PATHNAME &
                RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            ), 
            RecursiveIteratorIterator::SELF_FIRST
            //RecursiveIteratorIterator::CHILD_FIRST
            //RecursiveIteratorIterator::LEAVES_ONLY
        );/**/
    /*$i = new LimitRecursiveIteratorIterator(
            new DirectoryOnlyIterator($path), 
            RIT_SELF_FIRST, 
            10
        );/**/
    $i = new LimitRecursiveIteratorIterator(
            new DirectoryOnlyIterator($path), 
            RecursiveIteratorIterator::SELF_FIRST, 
            10
        );
    $n = 1;
    foreach ( $i as $w ){
        //$n++;
        echo $n++.'. <b>'.$w->getPathName().'</b><br />';
    }
    
    
}
catch(Exception $e){
    //echo 'No files found!<br/>';
    echo $e;
} 

$time_end = microtime(true);
echo '<br/><b>Script completed in '.($time_end-$time_start).'ms</b>';


?></pre>