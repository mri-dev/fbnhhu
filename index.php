<?php
    require_once 'etc/includes/init.php';

    if ( preg_match('#fbn-h.eu#', $_SERVER['HTTP_HOST']) )
    {
	header('Location: http://fbn-h.hu');
	
	exit;
    }

    if ( is_object($_CONTENT) && method_exists($_CONTENT, 'display') ) 
    {
	if ( in_array($_REQUEST['_CONTROLLER'], array()) || in_array($_REQUEST['_ACTION'], array()) || $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || $_REQUEST['xhr'] == 1 ) 
	{
	    $_CONTENT->display();
	}
	else
	{
    	    /**
	     * Display Template And Content
	     */
    	    $_TEMPLATE->open();

	    $_CONTENT->display();

	    $_TEMPLATE->close();
	}
    }
?>