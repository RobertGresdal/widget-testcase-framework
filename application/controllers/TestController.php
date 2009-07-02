<?php

/**
 * TestController is the controller for running and displaying the
 * result of the unit tests
 */
class TestController extends Zend_Controller_Action
{
    /**
     * Runs all the unit tests we can find
     */
    public function runAction()
    {
		/** FIXME: just a quick draft, copied from another file
			and not expected to work. */
        $view = Zend_Registry::getInstance()->view;
        $registry = Zend_Registry::getInstance();
        
        $path = realpath($registry->configuration->unittest->dir);
        //$template = 'list/testcases.tpl';
        
        /// copied from test_all.php in simpletest folder
        $suite = new TestSuite('All tests');
		/// TODO for all tests matching TestOf.+/.class/.php in folder {
			// $suite->addTestCase( new Class in file );
        	$suite->addTestCase(new TestOfSimpleTest());
        /// }
		//$suite->addTestCase(new TestOfRequestHandle());
		$suite->run(new HtmlReporter());
        /////////////
    }
}