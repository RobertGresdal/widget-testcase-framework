<?
$path = realpath('../../library/simpletest/');
//set_include_path(get_include_path() . PATH_SEPARATOR . $path);
//include('simpletest.php');
require_once($path.'autorun.php');
//echo get_include_path();

function ts_autoload($class){
    $file = null;
    switch($class){
        case 'TestOfSimpleTest':
        case 'TestOfRequestHandle':
            $file = $class.'.test.php';
            break;
    }
    if($file!==null)return require_once($file);
}
spl_autoload_register('ts_autoload');
