<?php
// PHP CORE ERROR REPORTING - Set the error reporting to strict so we
// get full feedback on any potential problems. 
//error_reporting(E_STRICT);
error_reporting(E_ALL);
//set_error_handler();
//restore_error_handler();

// We recieved a strict error telling us it was not safe to rely on
// the system's timezone settings and were urged to set a specific one. 
// TODO get this from the config
date_default_timezone_set('Europe/Paris');

// APPLICATION CONSTANTS - Set the constants to use in this application.
// These constants are accessible throughout the application, even in ini 
// files. We optionally set APPLICATION_PATH here in case our entry point 
// isn't index.php (e.g., if required from our test suite or a script).
defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', dirname(__FILE__));

defined('LIBRARY_PATH')
	or define('LIBRARY_PATH', APPLICATION_PATH.'/../library');
    
defined('APPLICATION_ENVIRONMENT')
    or define('APPLICATION_ENVIRONMENT', 'development');


// INCLUDE PATH - Add the "library" directory to the include_path, so 
// that PHP can find our Zend Framework classes.
set_include_path(
    LIBRARY_PATH . PATH_SEPARATOR . get_include_path()
);

// AUTOLOADER - Set up autoloading.
// This is a nifty trick that allows ZF to load classes automatically so
// that you don't have to litter your code with 'include' or 'require'
// statements.
require_once('zend/Loader.php');
require_once(LIBRARY_PATH.'/app/wtf2.class.php');
// register autoloaders
Zend_Loader::registerAutoload('WTF2');
Zend_Loader::registerAutoload();

// FRONT CONTROLLER - Get the front controller.
$frontController = Zend_Controller_Front::getInstance();

// APPLICATION ENVIRONMENT - Set the current environment.
// Set a variable in the front controller indicating the current environment --
// commonly one of development, staging, testing, production, but wholly
// dependent on your organization's and/or site's needs.
$frontController->setParam('env', APPLICATION_ENVIRONMENT);

// ROUTER - Set up my own router
$router = $frontController->getRouter();
//$router->removeDefaultRoutes();
// Make the index page list testcases
$router->addRoute('index',
    new Zend_Controller_Router_Route_Static(
        '',
        array('controller'=>'list', 'action'=>'display')
    )
);
$router->addRoute('list', 
    new Zend_Controller_Router_Route_Regex(
        'list(/(.*))?',
        array('controller'=>'list', 'action'=>'display')
    )
);
$router->addRoute('view', 
    new Zend_Controller_Router_Route_Regex(
        'view(/(.*))?',
        array('controller'=>'view', 'action'=>'display') 
    )
);
$router->addRoute('download',
    new Zend_Controller_Router_Route_Regex(
        'download(/(.*))?',
        array('controller'=>'build','action'=>'download')
    )
);
$router->addRoute('install',
    new Zend_Controller_Router_Route_Regex(
        'install(/(.*))?',
        array('controller'=>'build','action'=>'install')
    )
);
// UNIT TESTING ROUTER - For all environments that is not production
// FIXME Or is it better to do testing without relying on Zend to be working? I'm thinking we should assume Zend is working, we're not testing if PHP and the OS is working either...
if( $frontController->getParam('env') != 'production' ) { 
	$router->addRoute('test',
	    new Zend_Controller_Router_Route_Regex(
	        'test(/(.*))?',
	        array('controller'=>'test', 'action'=>'run')
	    )
	);
}

// CONTROLLER DIRECTORY SETUP - Point the front controller to your action
// controller directory.
$frontController->setControllerDirectory(APPLICATION_PATH . '/controllers');

// CONFIGURATION - Setup the configuration object
// The Zend_Config_Ini component will parse the ini file, and resolve all of
// the values for the given section.  Here we will be using the section name
// that corresponds to the APP's Environment
$configuration = new Zend_Config_Ini(
    APPLICATION_PATH . '/config/app.ini', 
    APPLICATION_ENVIRONMENT
);


// REGISTRY - setup the application registry
// An application registry allows the application to store application 
// necessary objects into a safe and consistent (non global) place for future 
// retrieval.  This allows the application to ensure that regardless of what 
// happends in the global scope, the registry will contain the objects it 
// needs.
$registry = Zend_Registry::getInstance();
$registry->configuration = $configuration;


// LAYOUT SETUP - Setup the layout component
// The Zend_Layout component implements a composite (or two-step-view) pattern
// With this call we are telling the component where to find the layouts scripts.
Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
$layout = Zend_Layout::getMvcInstance();
$layout->config = $registry->configuration;


// VIEW SETUP - Initialize properties of the view object
// The Zend_View component is used for rendering views. Here, we grab a "global" 
// view instance from the layout object, and specify the doctype we wish to 
// use. In this case, XHTML1 Strict.
//$view = Zend_Layout::getMvcInstance()->getView();
//$view->doctype('XHTML1_STRICT');
$view = new Zend_View_Smarty();
//Zend_Layout::getMvcInstance()->setView($view); // TODO: enable. see http://framework.zend.com/manual/en/zend.layout.advanced.html
$registry->view = $view;

// CLEANUP - remove items from global scope
// This will clear all our local boostrap variables from the global scope of 
// this script (and any scripts that called bootstrap).  This will enforce 
// object retrieval through the Applications's Registry
unset($frontController, $view, $configuration, $registry, $layout);