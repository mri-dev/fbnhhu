<?php
function &get_controller ($name, $mod=null, $force = false) 
{
    $obj = __get_class($name, 'controller', $force);

    if ( is_object($obj) ) 
    {
	parse_str($mod, $values);

	foreach ( $values as $key => $value ) 
	{
	    $obj->$key = $value;
	}
    }
    
    return $obj;
}

function &get_module ($name) 
{
    return __get_class($name, 'module');
}

function display_controller ($name, $mod=null) 
{
    if ( ($_CONTR = get_controller($name, null, true)) ) 
    {
	if ( is_object($_CONTR) && method_exists($_CONTR, 'display') ) 
	{
	    $_CONTR->display($mod);
	}
    }
}

function prepare_config ($file, $loadmodule = true) {
    $ret = array();

    if ( file_exists($file) ) {
	$lines = file($file);

	foreach ( $lines as $line ) {
	    $slices = array_map('trim', preg_split('#\s|\t#', $line, -1, PREG_SPLIT_NO_EMPTY));
	    /**
	     * Required minimum 2 elements
	     */
	    if ( count($slices) < 2 ) continue;
	    
	    /**
	     * Module name
	     */
	    if ( $slices[0] == 'ModuleName' ) {
		$ret['module_name'] = $slices[1];
	    }
	    
	    /**
	     * Controller require right (magic: LOGIN)
	     */
	    if ( $slices[0] == 'RequireRight' ) {
		$ret['require_right'] = strtolower($slices[1]);
		$ret['rights'][] = array('name' => strtolower($slices[1]));
	    }
	    
	    /**
	     * Action require right (magic: LOGIN)
	     */
	    if ( $slices[0] == 'ActionRight' ) {
		$ret['action_right'][] = strtolower($slices[1]);
		$ret['rights'][] = array('name' => strtolower(($ret['require_right'] ? $ret['require_right'] . '-' : '' ) . $slices[1]));
	    }
	    
	    /**
	     * Extra right
	     */
	    if ( $slices[0] == 'ExtraRight' ) {
		$ret['extra_right'][] = strtolower($slices[1]);
		$ret['rights'][] = array('name' => strtolower(($ret['require_right'] ? $ret['require_right'] . '-' : '' ) . $slices[1]));
	    }
	    
	    /**
	     * Extra right which need UID
	     */
	    if ( $slices[0] == 'ExtraUidRight' ) {
		$ret['extra_uid_right'][] = strtolower($slices[1]);
		$ret['rights'][] = array('name' => strtolower(($ret['require_right'] ? $ret['require_right'] . '-' : '' ) . $slices[1]), 'uid' => true);
	    }

	    /**
	     * Load required module
	     */
	    if ( $slices[0] == 'RequireModule' && $loadmodule ) {
		load_class($slices[1]);
	    }

	    /**
	     * Include required file
	     */
	    if ( $slices[0] == 'RequireFile' && file_exists(dirname($file) . '/' . $slices[1]) && $loadmodule ) {
		require_once dirname($file) . '/' . $slices[1];
	    }

	    /**
	     * Disable in Admin menu
	     */
	     if ( $slices[0] == 'DisableInMenu' ) {
	        $ret['disable_in_menu'] = $slices[1];
	     }
	}
    }

    return $ret;
}

function load_class ($name, $type='module') {
    global $_CLASSES;

    if ( isset($_CLASSES[$name . '_' . $type]) ) { return true;}

    $configDir = get_config(($type == 'controller' ? 'CONTROLLERS' : 'MODULES') . '::' . (!in_array($name, array('error', 'template')) && $_REQUEST['_ADMIN'] ? 'ADMIN' : '') . 'DIR');

    if ( file_exists(($file = $configDir . '/' . $name . '/' . $name . '.php')) ) {
	$_CLASSES[$name . '_' . $type] = array();
	$_CLASSES[$name . '_' . $type] = prepare_config($configDir . '/' . $name . '/.config');

	require_once $file;

	return true;
    }

    return false;
}

function &__get_class ($name, $type, $force = false) {
    global $_LOADED_CLASSES;

    $classname = $name . ($type == 'controller' ? '_controller' : '');

    if ( !isset($_LOADED_CLASSES[$classname]) || !is_object($_LOADED_CLASSES[$classname]) || $force ) 
    {
	$_LOADED_CLASSES[$classname] = null;

	if ( load_class($name, $type) && class_exists($classname) ) 
	{
	    $_LOADED_CLASSES[$classname] = new $classname();
        } 
        elseif ( $name !== 'error' && load_class('error') ) 
        {
	    display_controller('error', 'action=404&error=objektum (' . $classname . ') nem található');
        }
    }

    return $_LOADED_CLASSES[$classname];
}

function get_modules_rights () {
    $dir	= get_config('CONTROLLERS::ADMINDIR');
    $dirs	= scandir($dir);

    $ret	= array();

    if ( count($dirs) )
    {
	foreach ($dirs as $d) {

	    if ( $d[0] == '.' )
	    {
		continue;
	    }

	    $config = prepare_config($dir . '/' . $d . '/.config', false);

	    if ( count($config['rights']) && !$config['disable_in_menu'] )
	    {
		foreach ( $config['rights'] as $c ) 
		{
		    $name	= get_config('ADMIN::RIGHTS::' . strtoupper($c['name']));

		    $ret[$name]	= array('right' => $c['name'], 'right_name' => ($name ? $name : $c['name']), 'uid' => $c['uid']);
		}
	    }
	}
    }

    return $ret;
}
