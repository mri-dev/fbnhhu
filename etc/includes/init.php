<?php
    ob_start();
    session_start();

    header('Content-Type: text/html; charset=UTF-8', 1);
    setlocale(LC_ALL, 'hu_HU');

    require_once dirname(__FILE__) . '/../config.inc.php';
    require_once dirname(__FILE__) . '/functions.php';
    require_once dirname(__FILE__) . '/modules.php';

    if ( get_config('DEFAULT::DEBUG') )
    {
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	ini_set('display_errors', 1);
    }
    else
    {
	error_reporting(E_NONE);
	ini_set('display_errors', 0);
    }

    /**
     * Default Controller && Action
     */
    $_REQUEST['_CONTROLLER'] = get_config('CONTROLLERS::DEFAULT');
    $_REQUEST['_ACTION']     = get_config('CONTROLLERS::DEFAULT_ACTION');

    /**
     * Define variables
     */
    $_CLASSES		= array();
    $_MODULES		= array();

    /**
     * Check admin
     */
    $_REQUEST['_ADMIN']		= false;

    if ( preg_match('#^/admin/#i', $_SERVER['REQUEST_URI']) ) 
    {
        $_REQUEST['_ADMIN'] 	 = true;

	//$_REQUEST['_CONTROLLER'] = '';
        //$_REQUEST['_ACTION']	 = '';
    }

    /**
     * Prepare URI 
     * Get Controller && Action
     */
    if ( isset($_REQUEST['url']) ) 
    {
        $slices 			= explode('/', $_REQUEST['url']);

        $_REQUEST['_CONTROLLER']        = str_replace(array('-'), array('_'), array_shift($slices));
        $_REQUEST['_ACTION']            = array_shift($slices);
        $_REQUEST['_ID']		= array_shift($slices);
    }

    /**
     * Load default Modules
     */
    load_class('query');
    load_class('screen');
    load_class('redirect');

    /**
     * Create Database Connection
     */
    if (
    !mysql_pconnect(get_config('DB::HOST'), get_config('DB::USER'), get_config('DB::PASSWORD')) ||
    !mysql_select_db(get_config('DB::DATABASE')) ||
    !mysql_query('SET NAMES \'' . get_config('DB::CHARSET') . '\'') ) 
    {
	display_controller('error', 'action=500&error=adatbázis hiba');

	exit;
    }

    /**
     * require specific init file
     */
    if ( ($initfile = get_config('DEFAULT::INIT_FILE')) ) 
    {
	$initfile = dirname(__FILE__) . '/' . $initfile;
	
	if ( is_file($initfile) ) 
	{
	    require_once $initfile;
	}
    }

    /**
     * Get Session (User) && Design Controllers
     */
    $_SM	= &get_module('session');

    /**
     * Log in user
     */
     if ( isset($_REQUEST['lusername']) && isset($_REQUEST['lpassword']) ) 
     {
	if ( !$_SM->is_logged() ) 
	{
	    $_SM->login($_REQUEST['lusername'], $_REQUEST['lpassword'], true, (strlen($_REQUEST['lpassword']) == 32 ? false : true));
	}
    }

    /**
     * create template controller
     * reference session module
     */
    $_TEMPLATE		= &get_controller('template');
    $_TEMPLATE->sm	= &$_SM;

    if ( !isset($_REQUEST['url']) && $_SM->is_logged() )
    {
	if ( get_config('CONTROLLERS::LOGGED_DEFAULT') )
	{
	    $_REQUEST['_CONTROLLER'] = get_config('CONTROLLERS::LOGGED_DEFAULT');
	}

	if ( get_config('CONTROLLERS::LOGGED_DEFAULT_ACTION') )
	{
	    $_REQUEST['_ACTION']     = get_config('CONTROLLERS::LOGGED_DEFAULT_ACTION');
	}
    }

    /**
     * Check required Controller && Action available, display Error controller if not
     */
    if ( load_class($_REQUEST['_CONTROLLER'], 'controller') ) 
    {
	/**
	 * Check Rights if need
	 */
	$_CCONFIG	= $_CLASSES[$_REQUEST['_CONTROLLER'] . '_controller'];

	/**
	 * If is admin menu
	 * and user hasnt got admin right
	 * or config not need admin right and not logged in
	 */
	if ( $_REQUEST['_ADMIN']
	&& ( !$_SM->check_right('admin')
	&& ( !get_config('DEFAULT::USE_ADMIN_RIGHT') && !$_SM->is_logged() ) ) )
	{
	    $_REQUEST['_ACTION'] = '403';

	    $_CONTENT = get_controller('error', 'error=Hozzáférés megtagadva');
	}
	else 
	{
	    if ( isset($_CCONFIG['require_right']) && !$_SM->check_right($_CCONFIG['require_right'] . (count($_CCONFIG['action_right']) && in_array($_REQUEST['_ACTION'], $_CCONFIG['action_right']) ? '-' . $_REQUEST['_ACTION'] : '')) ) 
	    {
		$_REQUEST['_ACTION'] = '';
		$_CONTENT = get_controller('error', 'action=500&error=hozzáférés megtagadva.');
	    } 
	    else 
	    {
		$_SM->set_right(true);

		if ( !($_CONTENT = get_controller($_REQUEST['_CONTROLLER'], ($_REQUEST['_CONTROLLER'] == 'error' && isset($_REQUEST['_ERROR_ACTION']) ? $_REQUEST['_ERROR_ACTION']: ''))) || !method_exists($_CONTENT, 'display') ) 
		{
		    $_CONTENT = get_controller('error', 'action=500&error=nem létezik megjelenítő funkció.');
		}
	    }
	}
    } 
    else 
    {
        $_CONTENT = get_controller(get_config('CONTROLLERS::MISSING_CONTROLLER'), get_config('CONTROLLERS::MISSING_CONTROLLER_PARAMS'));

        /**
         * Check Rights if need
        */
        $_CCONFIG = $_CLASSES[$_REQUEST['_CONTROLLER'] . '_controller'];

        if ( ($_REQUEST['_ADMIN'] && !$_SM->check_right('admin')) )
        {
            $_REQUEST['_ACTION'] = 'login';
            $_CONTENT = get_controller('index');
        }
    }
?>
