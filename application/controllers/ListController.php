<?php

/**
 * ListController is the controller for showing a list of all testcases.
 */
class ListController extends Zend_Controller_Action
{
    /**
     * The "index" action is the default action for all controllers. This 
     * will be the landing page of your application.
     *
     * Assuming the default route and default router, this action is dispatched 
     * via the following urls:
     *   /
     *   /index/
     *   /index/index
     *
     * @return void
     */
//    public function indexAction()
//    {
        /*
           Display the root tree
        */ 
//    }
    
    public function displayAction()
    {
        $registry = Zend_Registry::getInstance();
		$view = $registry->view;
         
		$request = $this->getRequest();
		/*
		 * $request->getParam(1) returns the path requested
		 * Example: '/foo' from 'www.example.com/list/foo'
		 * (It starts with a '/' so it will separate it from the testcase dir)
		 */
        $path = $registry->configuration->testcase->dir . $request->getParam(1);
        $rpath = realpath($path);
        $template = 'list/testcases.tpl';
        
        // using a realpath as the cahe id, so the only valid choices are valid paths
        if( $rpath===false ){
            /** Use the default dir is the requested path was incorrect
             * @todo P1: What to do if the default testcase dir is incorrect? */
            $rpath = realpath($registry->configuration->testcase->dir.'/');
            $view->warning = 'Requested path was not found. Using default path.';
        } 
        
        $tc = new WidgetTC($rpath);
        if( $tc->valid() ){
            // The current is a single testcase
            $wit = array($tc->getFileIterator());
        } else {
            // display a full list
            // Makes a (limited) directory iterator
            $it = new LimitRecursiveIteratorIterator(
                    new DirectoryOnlyIterator($rpath,
                        DirectoryOnlyIterator::IGNORE_SVN |
                        DirectoryOnlyIterator::IGNORE_CVS
                        ), 
                    RecursiveIteratorIterator::SELF_FIRST, 
                    $registry->configuration->testcase->max_scan_dir
                );
            // Wraps a filteriterator over the recursive iterator 
            // and now returns only widget testcases
            $wit = new WidgetIterator($it);
        }
        
        // Use the realpath as the cache id. This will make sure we can't get infinite 
        // caches, since the max number is number of folders within the testcase dir
        $cache_id = $rpath;
        // Set caching if it is set in the ini file
        $view->set_caching($registry->configuration->render->caching);
        if(!$view->is_cached($template, $cache_id)){
            $view->base_tc_path = $registry->configuration->testcase->dir.'/';
            $view->iterator = $wit;
            $view->basedir = $registry->configuration->webapp->basedir;
            $view->current_path = $request->getParam(2); // getParam(2) returns path without prefixing '/'
        }
        
        echo $view->render($template, $cache_id);
    }
}