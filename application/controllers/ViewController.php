<?php

/**
 * ViewController is the controller for showing a list of all files in a widget
 * or show the file itself, depending on if the request asks for a file or a dir
 */
class ViewController extends Zend_Controller_Action
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
        $path = $registry->configuration->testcase->dir.'/'.$this->getRequest()->{2};
        $rpath = realpath($path);
        
        if( is_dir($rpath) ){
            $this->_displayFolder();
        } else {
            $this->_displayFile();
        }
    }
    
    private function _displayFolder(){
        $registry = Zend_Registry::getInstance();
        $request = $this->getRequest();
        $path = $registry->configuration->testcase->dir.'/'.$request->getParam(2);
        $rpath = realpath($path);
        $view = Zend_Registry::getInstance()->view;
        $template = 'view/folder.tpl';
        
        // using a realpath as the cahe id, so the only valid choices are valid paths
        if( $rpath===false ){
            /** Use the default dir is the requested path was incorrect
             * @todo P1: What to do if the default testcase dir is incorrect? */
            $rpath = realpath($registry->configuration->testcase->dir.'/');
            $view->assign('warning','Requested path was not found. Using default path.');
        } 
        
        $tc = new WidgetTC($rpath);
        $it = $tc->getFileIterator(WidgetTC::FORCE_WIDGET, array('.svn','.cvs'));
        
        $view->set_caching($registry->configuration->render->caching);
        // We set the cache ID as the requested path, so every directory list is cached 
        $cache_id = md5( $request->getParam(2) );
        if(!$view->is_cached($template, $cache_id)){
            $view->base_tc_path = realpath($registry->configuration->testcase->dir.'/');
            $view->iterator     = $it;
            $view->basedir      = $registry->configuration->webapp->basedir;
            $view->current_path = $request->getParam(2);
            $view->parentdir    = $view->current_path.'/..';
            if( !$tc->valid() ){
                $view->warning  = 'The current path does not point to a valid widget';
            } else {
                $view->tc = $tc;
            }
        }
        
        echo $view->render($template, $cache_id);
    }
    
    private function _displayFile(){
        $registry = Zend_Registry::getInstance();
        
        // Print the contents of the file
        $file = $registry->configuration->testcase->dir.'/'.$this->getRequest()->getParam(2);
        if ($stream = fopen($file, 'r')) {
            // Disable layout
            $layout = Zend_Layout::getMvcInstance();
            $layout->disableLayout();
        
            /// @todo: output headers and file information
            $mm = new MimeMagic();
            /// @todo: fix in mimemagic instead
            header('Content-type: '.$mm->get(realpath($file)));
            
            echo stream_get_contents($stream);
            fclose($stream);
        } else {
            throw new Exception("Attempted to view a non-existing file \"${file}\".");
        }
        
    }
}
