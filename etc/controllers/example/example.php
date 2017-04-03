<?php

class _controller {
    private $scr;

    function __construct () 
    {
	$this->scr	= new screen(__FILE__);
    }

    function display ($params = null) 
    {
        parse_str($params);

        $this->action = isset($action) ? strtolower($action) : $_REQUEST['_ACTION'];

        switch ( $this->action ) 
        {
    	    default : 
    		break;
        }
    }
}
