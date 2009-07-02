<?php
/**
* @package library
*/


/**
* Interface class that currently speaks with Smarty
* 
* Depends on Config and Smarty
* @see Config
* @see Smarty
*/
class Render {
    /*private $smarty;
    private $config;
    
    public function __construct(){
        $this->smarty = new Smarty;
        $c = new Config;
        $this->config = $c->render;
        
        $this->smarty->template_dir = $this->config->template_dir;
        $this->smarty->compile_dir  = $this->config->compile_dir;
        $this->smarty->cache_dir    = $this->config->cache_dir;
        $this->smarty->config_dir   = $this->config->config_dir;
    }
    */
    
    /**
    * Creates and configures a new Smarty object
    * @return Smarty
    */
    public static function factory(){
        $s = new Smarty;
        $c = new Config;
        $s->template_dir = $c->render->template_dir;
        $s->compile_dir = $c->render->compile_dir;
        $s->cache_dir = $c->render->cache_dir;
        $s->config_dir = $c->render->config_dir;
        
        return $s;
    }
}


?>