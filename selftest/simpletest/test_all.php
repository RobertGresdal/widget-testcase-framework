<?php
require_once('bootstrap.php');

//require_once('../../autoload.php');
//require_once('../../application/bootstrap.php');
//require('../../library/app/wtf2.class.php');
// todo: print the result, don't use a zend viewer, keep this simple!
//Zend_Loader::registerAutoload('Tester');


$suite = new TestSuite('All tests');
/// @todo add all files in this dir that starts with 'TestOf'
$suite->addTestCase(new TestOfSimpleTest());
//$suite->addTestCase(new TestOfRequestHandle());
$suite->run(new HtmlReporter());
/**/

/*class AllTests extends TestSuite {
    function AllTests(){
        $this->TestSuite('All tests');
        $this->addFile('test_simpletest.php');
        $this->addFile('test_foo.php');
    }
}/**/
?>