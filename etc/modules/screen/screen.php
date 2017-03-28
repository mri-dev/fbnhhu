<?php

class screen {
    private $dir;
    public $form;
    public $table;
    public $sm;
    
    function screen ($__FILE__) 
    {
	$this->dir	= dirname($__FILE__);
	$this->form	= new form();
	$this->table	= new table();

	if ( basename($__FILE__) !== 'error.php' ) 
	{
	    $this->sm	= &get_module('session');
	}
    }

    function register_var ($var, $val) 
    {
	$this->$var = $val;
    }

    function register_pointer ($var, &$val) 
    {
	$this->$var = &$val;
    }

    function set_screen ($f) 
    {
	$file = $this->dir . '/screens/' . mb_strtolower($f) . '.php';
	
	if ( is_file($file) ) require_once $file; else 
	{
	    display_controller('error', 'action=404&error=screen (' . $f . ') nem található');
	}
    }

    function header ($name, $id = null, $class = null) 
    {
	echo '<h2 class="page_header' . ($class ? ' ' . $class : '') . '"' . ($id ? ' id="' . $id . '"' : '') . '>' . $name . '</h2>';
    }

    function message ($message, $class = null) 
    {
	echo '<div class="message' . ($class ? ' ' . $class : '') . '"><div class="right">' . $message . '</div></div>';
    }

    function is_action ($action) 
    {
	return strtolower($action) == $_REQUEST['_ACTION'];
    }
    
    function get_action () 
    {
	return $_REQUEST['_ACTION'];
    }
}