<?php
    require_once dirname(__FILE__) . '/../etc/includes/init.php';

    /**
     * Display Template And Content
     */
    if ( ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || in_array($_REQUEST['_CONTROLLER'], array()) || $_REQUEST['xhr'] == 1) && is_object($_CONTENT) && method_exists($_CONTENT, 'display') )
    {
	$_CONTENT->display();
    }
    else
    {
	$_TEMPLATE->open();

	if ( is_object($_CONTENT) && method_exists($_CONTENT, 'display') )
	{
      echo '<div class="page-width-holder"><div class="content-holder">';
	    $_CONTENT->display();
      echo '</div></div>';
	}

	$_TEMPLATE->close();
    }
