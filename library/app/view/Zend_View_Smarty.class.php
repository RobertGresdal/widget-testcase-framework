<?php
class Zend_View_Smarty implements Zend_View_Interface
{
    /**
     * Smarty object
     * @var Smarty
     */
    protected $_smarty;


    /**
     * Constructor
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct($tmplPath = null, $extraParams = array())
    {
        $this->_smarty = new Smarty;

        // Set default configuration
        $registry = Zend_Registry::getInstance();
        $c = $registry->configuration;
        
        if( ($s = $c->render->template_dir) && is_readable($s) &&
            ($s = $c->render->compile_dir) && is_readable($s) &&
            ($s = $c->render->cache_dir) && is_readable($s) &&
            ($s = $c->render->config_dir) && is_readable($s) 
        ){
            $this->_smarty->template_dir = $c->render->template_dir;
            $this->_smarty->compile_dir = $c->render->compile_dir;
            $this->_smarty->cache_dir = $c->render->cache_dir;
            $this->_smarty->config_dir = $c->render->config_dir;
        } else {
            throw new Exception("Path \"${s}\" was not readable. Please change your ini file.");
        }
        
        if (null !== $tmplPath) {
            $this->setScriptPath($tmplPath);
        }

        foreach ($extraParams as $key => $value) {
            $this->_smarty->$key = $value;
        }
        
        // Load extra plugins if defined in the configuration
        if(strlen($c->render->plugin_folder)>0){
            $this->loadExtraPlugins($c->render->plugin_folder);
        }
    }

    /**
     * Return the template engine object
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        }

        throw new Exception('Invalid path provided');
    }
    public function addScriptPath($path)
    {
        $this->setScriptPath($path);
    }

    /**
     * Retrieve the current template directory
     *
     * @return string
     */
    public function getScriptPaths()
    {
        return array($this->_smarty->template_dir);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function setBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function addBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Assign a variable to the template
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set($key, $val)
    {
        $this->_smarty->assign($key, $val);
    }

    /**
     * Retrieve an assigned variable
     *
     * @param string $key The variable name.
     * @return mixed The variable value.
     */
    public function __get($key)
    {
        return $this->_smarty->get_template_vars($key);
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return (null !== $this->_smarty->get_template_vars($key));
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->_smarty->clear_assign($key);
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or
     * array of key => value pairs)
     * @param mixed $value (Optional) If assigning a named variable,
     * use this as the value.
     * @return void
     */
    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }

        $this->_smarty->assign($spec, $value);
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Zend_View either via
     * {@link assign()} or property overloading
     * ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_smarty->clear_all_assign();
    }

    /**
     * Processes a template and returns the output.
     *
     * @param string $name The template to process.
     * @return string The output.
     */
    public function render($name, $cache_id=null)
    {
        if( is_null($cache_id) ){
            return $this->_smarty->fetch($name);
        } else {
            return $this->_smarty->fetch($name, $cache_id);
        }
    }
    
    /**
     * Checks to see if the current items is cached.
     * @param string $template The tempalte to check
     * @return boolean True if the template was cached
    */
    public function is_cached($template, $cache_id=null)
    {
        if( is_null($cache_id) ){
            return $this->_smarty->is_cached($template);
        } else { 
            return $this->_smarty->is_cached($template,$cache_id);
        }
    }
    
    /**
    * @todo change name to camelcasing
    */
    public function set_caching($caching)
    {
        $this->_smarty->caching = $caching;
    }
    
    /**
    * @todo change name to camelcasing
    */
    public function register_function($internal_name, $external_name){
        $this->_smarty->register_function($internal_name, $external_name);
    }
    
    /**
    * Loads all *.function.php files in the folder and register the function
    */
    public function loadExtraPlugins($path){
        $i = new DirectoryIterator($path);
        foreach($i as $file){
            // Adds custom functions and modifiers
            if( preg_match('/^function\.(.+)\.php$/',$file, $match) ){
                /** @todo include_once instead and rather log the error? */
                require_once($file->getPathname());
                $this->_smarty->register_function($match[1],'smarty_function_'.$match[1]);
            } else if( preg_match('/^modifier\.(.+)\.php$/',$file,$match) ){
                /** @todo include_once instead and rather log the error? */
                require_once($file->getPathname());
                $this->_smarty->register_modifier($match[1],'smarty_modifier_'.$match[1]);
            }
        }
    }
    
}
?>