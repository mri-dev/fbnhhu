<?php
    function _implode ($sep, $vals, $varname)
    {
	$arr = array();

	foreach ( $vals as $val )
	{
	    $arr[] = (is_array($val) ? $val[$varname] : $val->$varname);
	}
	
	return implode($sep, $arr);
    }

    function icon ($icon, $alt = '', $url = '', $url_class = '')
    {
	$img = '<img src="/images/icons/icon_' . $icon . '.png" alt="' . $alt . '" title="' . $alt . '" />';

	if ( $url )
	{
	    $img = '<a href="' . $url . '" class="' . $url_class . '">' . $img . '</a>';
	}
	
	return $img;
    }

    function is_valid_email ($email) 
    {
	return preg_match ( '/^[[:alnum:]][a-z0-9A-Z\_\.\-]*@[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,6}$/', $email );
    }

    function sort_object (&$list, $param, $array = false)
    {
        usort($list, create_function('$a, $b', 'return strnatcasecmp(' . ($array ? '$a[' . $param . ']' : '$a->' . $param . '') . ', ' . ($array ? '$b[' . $param . ']' : '$b->' . $param . '') . ');'));

        return $list;
    }

    function sort_array (&$list, $param)
    {
        return sort_object($list, $param, true);
    }


    function price ($p, $round = false, $cur = true) 
    {
        return number_format(($round ? round_price($p) : $p), 0, '', '.') . ($cur ? ' Ft' : '');
    }

    function round_price ($p) 
    {
	return (round($p / 5) * 5);
    }

    function get_config ($n, $d=false) 
    {
        global $_CONFIG;
        static $_CONFIG_CACHE;
	
	if ( !isset($_CONFIG_CACHE[$n]) ) 
	{
    	    $config = $_CONFIG;
    	    $params = explode('::', $n);

    	    foreach ( $params as $param ) 
    	    {
    		if ( !isset($config[$param]) ) 
    		{
    		    return false;
		}

    		$config = $config[$param];
    	    }
        
	    $_CONFIG_CACHE[$n] = $config;
	}

        return $_CONFIG_CACHE[$n];
    }

    function urlfriendly ($str) 
    {
        STATIC $strtr  = array(' ' => '-',
    				'/' => '-',
                                'á' => 'a',
                                'é' => 'e',
                                'ő' => 'o',
                                'ú' => 'u',
                                'ű' => 'u',
                                'ó' => 'o',
                                'ü' => 'u',
                                'ö' => 'o',
                                'í' => 'i',
                                );

        return preg_replace(array('#[^a-z0-9\_\-]#i', '#\-+#'), array('', '-'), strtr(trim(mb_strtolower($str, 'UTF-8')), $strtr));
    }

    function __setcookie ($var, $value, $expire = 0) 
    {
	setcookie($var, $value, $expire, '/', '.' . get_config('DEFAULT::COOKIEURL'), false, false);
	$_COOKIE[$var] = $value;

	return true;
    }

    function form_error ($name = null, $error = null) 
    {
	global $_FORM_ERRORS;

	if ( !$name )
	{
	    return count($_FORM_ERRORS);
	}
	
	if ( is_array($name) ) 
	{
	    foreach ( $name as $k => $v ) $_FORM_ERRORS[$k] = $v;
	    
	    return true;

	}
	elseif ( !$error && isset($_FORM_ERRORS[$name]) ) 
	{
	    return $_FORM_ERRORS[$name];
	}
	elseif ( $error )
	{
	    $_FORM_ERRORS[$name] = $error;
	}
	else
	{
	    return false;
	}
    }

    function js() 
    {
        static $_JS = array();

        $args = func_get_args();

        return $args ? $_JS = array_merge($_JS, $args) : array_unique($_JS);
    }

    function title($t = null) 
    {
	static $_TITLE;
	
	if ( $t ) 
	{
	    $_TITLE = $t;
	}
	else 
	{
	    return $_TITLE;
	}
    }

    function css() 
    {
        static $_CSS = array();

        $args = func_get_args();

        return $args ? $_CSS = array_merge($_CSS, $args) : array_unique($_CSS);
    }
    
    function format_date ($date, $str = false, $time = false, $year = false) 
    {
	static $days 	= array(1 => 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap');
	static $months 	= array(1 => 'Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');

	$strtime = $str ? $date : strtotime($date);
	$day = date('N', $strtime);

	if ( !$strtime )
	{
	    return null;
	}

	return ($year && date('Y', $strtime) == date('Y') ? '' : date('Y', $strtime)) . '. ' . $months[date('n', $strtime)] . ' ' . date('d', $strtime) . '., ' . $days[$day] . ($time ? ' (' . date('H:i', $strtime) . ')' : '');
    }

    function mail_from_file ($email, $subject, $f, $vars, $nl2br = true, $files = array()) 
    {
	$file = get_config('DEFAULT::MAILDIR') . '/' . $f . '.txt';

	if ( is_file($file) ) 
	{
	    $text = preg_replace('#%([a-zA-Z_0-9]+)%#e', '$vars[\'$1\']', file_get_contents($file));

	    return htmlmail($email, $subject, ($nl2br ? nl2br($text) : $text), $files);
	}
	
	return false;
    }

    function htmlmail($email, $subject, $body, $files = array())
    {
        $body = "<!DOCTYPE html>\n".
                "<html>\n".
                "<head>\n".
                "<style type=\"text/css\"><!--\n".
                "a { color: #50a546; text-decoration: none; font-weight: bold; }".
                "--></style>\n".
                "</head>\n\n".
                "<body style=\"font: normal 12px/15px Verdana; background-color: #f1f1f1; color: #4a4c4c;\" align=\"center\">\n\n".

		"<div align=\"center\"><img src=\"cid:logo\" alt=\"\" align=\"center\" width=\"155\" /><br />" .
		"<h1 style=\"font: italic normal 20px/25px Georgia;\">" . $subject . "</h1></div><br />" .
                "<table width=\"90%\" align=\"center\" style=\"border-right: 1px solid #bebebe; border-left: 1px solid #bebebe; font: normal 12px/15px Verdana;\" cellspacing=\"10\">\n" .

                "<tr><td align=\"left\">\n" .

                $body.

                "</td></tr>\n" .
		"</table><br />\n" .
		
		"<div align=\"center\"><img src=\"cid:logo\" alt=\"\" align=\"center\" width=\"155\" /></div>" .

                "</body>\n".
                "</html>";

        return phpmail($email, $subject, $body, $files);
    }

    function phpmail ($email, $subject, $body, $files = array()) 
    {
	require_once dirname(__FILE__) . '/swiftmailer/lib/swift_required.php';

	$mail = Swift_Message::newInstance();

	$mail->setSubject($subject);
	$mail->setFrom(array(get_config('DEFAULT::EMAIL_FROM') => get_config('DEFAULT::EMAIL_NAME')));
	$mail->setTo($email);

        if ( strpos($body, 'cid:logo') !== false )
        {
            $cid = $mail->embed(Swift_Image::fromPath(dirname(__FILE__) . '/../../images/logo-email.png'));

            $body = str_replace('cid:logo', $cid, $body);
        }

	if ( count($files) )
	{
	    foreach ( $files as $file )
	    {
		$mail->attach(Swift_Attachment::fromPath($file)->setFilename(basename($file)));
	    }
	}

	$mail->setBody($body, 'text/html');
	$mail->addPart(strip_tags($body), 'text/plain');

	$transport	= Swift_SmtpTransport::newInstance('127.0.0.1', 25);
	$mailer		= Swift_Mailer::newInstance($transport);

	$mailer->send($mail);
    }

    function admin_links (&$session) 
    {
	global $_SM;

	$menu_names	= get_config('ADMIN::MENU');
	$controllers	= get_config('CONTROLLERS::ADMINDIR');

	if ( is_dir($controllers) )
	{
	    $dirs = scandir($controllers);
	    
	    foreach ( $dirs as $dir )
	    {
		$config_file = $controllers . '/' . $dir . '/.config';
		
		if ( $d[0] == '.' || in_array($dir, array('index', 'admin', 'template')) || !file_exists($config_file) )
		{
		    continue;
		}
		
		$params = prepare_config($config_file);

		if ( !isset($params['disable_in_menu']) && (!isset($params['require_right']) || $_SM->check_right($params['require_right'])) )
		{
		    $ret[] = array(
			'name' 		=> (isset($menu_names[$dir]) ? $menu_names[$dir] : $dir), 
			'link' 		=> $dir,
			'module_name'	=> $dir,
		    );
		}
	    }

	    if ( $ret )
	    {
		$ret = sort_array($ret, 'name');
	    
		return $ret;
	    }
	}
	
	return array();
    }

    function get_si ($bytes)
    {
        if ( ($bytes / 1024 / 1024 / 1024) >= 1 )
        {
            $ret = round(($bytes / 1024 / 1024 / 1024), 2) . 'GB';
        }
        elseif ( ($bytes / 1024 / 1024) >= 1 )
        {
            $ret = round(($bytes / 1024 / 1024), 0) . 'MB';
        }
        elseif ( ($bytes / 1024) >= 0 )
        {
            $ret = round(($bytes / 1024), 0) . 'kB';
        }
        else
        {
            $ret = $bytes . 'B';
        }

        return $ret;
    }

    function is_available ($available)
    {
	return '<img src="/images/icons/icon_' . ($available ? 'success' : 'delete') . '.png" alt="' . ($available ? 'igen' : 'nem') . '" />';
    }
