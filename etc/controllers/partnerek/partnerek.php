<?php

class partnerek_controller {
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
            $this->scr->register_var('MEMBERS', get_config('MEMBERS::IMAGES'));
          	$this->scr->register_var('MEMBERS_IMAGEDIR', get_config('MEMBERS::IMAGEDIR'));
          	$this->scr->register_var('MEMBERS_IMAGEURL', get_config('MEMBERS::IMAGEURL'));

          	$this->scr->register_var('PARTNERS', get_config('PARTNERS::IMAGES'));
          	$this->scr->register_var('PARTNERS_IMAGEDIR', get_config('PARTNERS::IMAGEDIR'));
          	$this->scr->register_var('PARTNERS_IMAGEURL', get_config('PARTNERS::IMAGEURL'));
            $this->scr->set_screen('PARTNEREK');
    		  break;
        }
    }
}
 
