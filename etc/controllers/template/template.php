<?php
class template_controller {
    private $scr;
    private $user;

    private $grouplist;
    private $groups;

    function __construct ()
    {
    	$this->scr = new screen(__FILE__);

    	css('form', 'defaults', 'jquery.chosen', 'jquery-ui', 'jquery.reveal', 'fbn');
    	js('jquery', 'ckeditor/ckeditor', 'jquery.chosen', 'jquery.upload', 'ckeditor/adapters/jquery', 'ckfinder/ckfinder', 'jquery-ui', 'jquery.ui.datepicker-hu', 'jquery.reveal', 'jquery.sharrre-1.3.2.min', 'functions', 'fbn', 'slideshow');

    	title(get_config('DEFAULT::TITLE'));

    	$this->grouplist = new grouplist();
    	$this->groups	 = $this->grouplist->get_tree(1, false, $this->scr->sm->is_logged());

    	$this->scr->register_var('GROUPS', $this->groups); 
    }

    function open ()
    {
	global $_CONTENT;

        $this->scr->register_var('CONTENT', $_CONTENT);
        $this->scr->register_var('MENU_ITEMS', admin_links($this->scr->sm->user));

    	$this->scr->set_screen('HEADER');

	if ( $this->scr->sm->is_logged() )
	{
	    $this->scr->set_screen('LOGGED_HEADER');
	}
    }

    function close ()
    {
	$this->scr->register_var('MEMBERS', get_config('MEMBERS::IMAGES'));
	$this->scr->register_var('MEMBERS_IMAGEDIR', get_config('MEMBERS::IMAGEDIR'));
	$this->scr->register_var('MEMBERS_IMAGEURL', get_config('MEMBERS::IMAGEURL'));

	$this->scr->register_var('PARTNERS', get_config('PARTNERS::IMAGES'));
	$this->scr->register_var('PARTNERS_IMAGEDIR', get_config('PARTNERS::IMAGEDIR'));
	$this->scr->register_var('PARTNERS_IMAGEURL', get_config('PARTNERS::IMAGEURL'));

	$this->scr->register_var('GROUPS_ABOUT', $this->grouplist->get_tree(15));
	$this->scr->register_var('GROUPS_FORUMS', $this->grouplist->get_tree(17));

	if ( $this->scr->sm->is_logged() )
	{
	    $this->scr->set_screen('LOGGED_FOOTER');
	}

	$this->scr->set_screen('FOOTER');
    }
}
