<?php
/**
* This is a required file. 
*
* @package wtf2
*/ 


/**
* This is the application class - for now only contains the
* autoload function.
*
* @todo P4 - Loading libraries such as pear-log and Smarty are 
* currently loadded using static paths. Make this configurable
* in an ini file where those paths are located.
*/
class WTF2 extends Zend_Loader {
    
    /*public static function registerAutoload(){
        spl_autoload_register(array('WTF2','loadClass'));
    }*/
    
    public static function autoload($class)
    {
        try {
            self::loadClass($class);
            return $class;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function loadClass($class){
        $path = dirname(__FILE__);
        $libraryPath = $path.'/../../library';
        $file = null;
        
        /*$registry = Zend_Registry::getInstance();
        $config = $registry->configuration;*/
        
        switch($class){
            /********************************************
            ** Classes belongig to application package **
            ********************************************/
            case 'Log':
            case 'WidgetIterator':
            case 'WidgetTC':
            case 'WTF_BWCompat':
                $file = $class.'.class.php';
                break;
            /***************************************************    
            **   Classes that helps build a widget testcase   **
            ***************************************************/
            case 'Builder':
            case 'WidgetBuilder':
            case 'WidgetConfigBuilder':
            case 'WidgetJSLibraryBuilder':
            	$file = $path.'/builder/'.$class.'.class.php';
            	break;
            /***************************************************
            ** Classes belonging to generic library functions **
            ***************************************************/
            case 'DirectoryOnlyIterator':
            case 'FilenameFilterIterator':
            case 'LimitRecursiveIteratorIterator':
            	$file = $path.'/iterators/'.$class.'.class.php';
            	break;
            /****************************
            **   View/Render classes   **
            ****************************/
            case 'Render':
            case 'Zend_View_Smarty':
                $file = $libraryPath.'/app/view/'.$class.'.class.php';
                break;
            /*************************************/
            case 'EventHandler': 
            	$file = $libraryPath.'/app/events/'.$class.'.class.php';
            	break;
            /*************************************/
            case 'Module':
            case 'ModuleObserver':
            	$file = $libraryPath.'/app/modules/'.$class.'.class.php';
            	break;
            /**************************************
            ** Special cases, imported libraries **
            **************************************/
            case 'MimeMagic':
                $file = $libraryPath.'/mime/MimeMagic.class.php';
                break;
            /*************************************/
            case 'Smarty':
                $file = $path.'/../smarty/Smarty.class.php';
                //$file = APPLICATION_PATH.'/../library/smarty/Smarty-2.6.20/libs/Smarty.class.php';
                //$file = $config->render->library.'/Smarty.class.php';
                /// @todo look in the config to get the path
                break;
            /***************************************
            **        Unit testcase module        **
            ***************************************/
            case 'UnitTestCase':
            case 'TestSuite':
            	$file = $libraryPath.'/simpletest/autorun.php';
                break;
            /*case 'File_Archive':
                $file = '/xampp/htdocs/pear/file_archive/File_Archive-1.5.4/Archive.php';
                break;/**/
            /**************************************
            **  Testcases                        **
            **************************************/
            case 'TestOfSimpleTest':
            case 'TestOfRequestHandle':
            case 'TestOfRequestHandler':
                $file = 'selftest/simpletest/'.$class.'.test.php';
                break;
            default:
                throw new Exception("Class \"{$class}\" not found in \"$file\".");
        }
        
        @include_once($file);
    }
}