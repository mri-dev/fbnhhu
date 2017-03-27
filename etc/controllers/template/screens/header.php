<?php
    echo '<!DOCTYPE html>',
	 '<html>',
	 '<head>',
	 '<title>' . title() . '</title>',

	 '<meta charset="UTF-8">',
	 '<link rel="shortcut icon" type="image/png" href="/favicon.png" />',

	 '<link rel="stylesheet" href="/css/' . implode('.css" media="screen" /><link rel="stylesheet" href="/css/', css()) . '.css?t='.microtime().'" />',
         '<script src="/js/' . implode('.js"></script><script src="/js/', js()) . '.js"></script>',

	 '<!--[if lt IE 9]>',
	 '<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>',
	 '<![endif]-->',

	 '<script type="text/javascript">',
	 'var _gaq = _gaq || [];',
	 '_gaq.push([\'_setAccount\', \'UA-30959967-1\']);',
	 '_gaq.push([\'_trackPageview\']);',
	 '(function() {',
	 'var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;',
	 'ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';',
	 'var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);',
	 '})();',
	 ' </script>',

	 '<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement(\'script\');
fbds.async = true;
fbds.src = \'//connect.facebook.net/en_US/fbds.js\';
var s = document.getElementsByTagName(\'script\')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
_fbq.push([\'addPixelId\', \'1538685546378235\']);
})();
window._fbq = window._fbq || [];
window._fbq.push([\'track\', \'PixelInitialized\', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=1538685546378235&amp;ev=PixelInitialized" /></noscript>',

	 '</head>',

	 '<body' . ($_REQUEST['_CONTROLLER'] ? ' class="' . urlfriendly($_REQUEST['_CONTROLLER']) . '"' : '') . '>',

	 '<header id="header">',
   '<div id="login-container" class="' . ($this->sm->is_logged() ? 'logged' : 'login') . '">',
	 '<div id="login-box">';

    if ( $this->sm->is_logged() )
    {
	echo '<div id="logged-name">Üdvözöllek <span class="bold">' . $this->sm->user->name . '</span>!<br /><a href="/admin/users/profil">Profil</a> &bull; <a href="/index/logout">Kilépés</a></div>';

        if ( $this->MENU_ITEMS )
        {
            echo '<nav id="user_menu">',
                 '<ul>';

            foreach ( $this->MENU_ITEMS as $item )
            {
                echo '<li class="' . urlfriendly($item['link']) . '"><a href="/admin/' . $item['link'] . '" title="' . $item['name'] . '"' . ($item['module_name'] == $_REQUEST['_CONTROLLER'] ? ' class="selected"' : '') . '>' . $item['name'] . '</a></li>';
            }

            echo '</ul>',
                 '</nav>';
	}
    }
    else
    {
        $this->form->nodiv	= true;
        $this->form->dl		= false;
        $this->form->sh_errors	= false;

	if ( isset($_REQUEST['lusername']) && isset($_REQUEST['lpassword']) )
	{
	    echo '<div id="login-error"><a href="/content/sugo">Elakadt? Segítségért ide klikkelhet!</a></div>';
	}

        $this->form->open();
        $this->form->input_text('Felhasználónév', 'lusername');
        $this->form->input_password('Jelszó', 'lpassword');
        $this->form->close('BELÉPÉS');
    }

    echo '</div>', # login-box
	 '</div>', # login-container

	 '<div id="header-container" class="">',
	 '<div id="logo"><h1><a href="/">FBN-H</a></h1></div>',

	 '<nav id="topmenu"><div class="page-width-holder">',
	 '<ul>';

    if ( $this->GROUPS )
    {
	$c = count($this->GROUPS);

	for ( $i = 0; $i < $c; $i++ )
	{
	    if ( $this->GROUPS[$i]->public != 1 && !$this->sm->is_logged() )
	    {
		continue;
	    }

	    if ( $i > 0 )
	    {
		$last_group = $this->GROUPS[$i - 1];

		/**
	         * hogy ha az elozo csoport melysege megegyezik a mostanival
	         * akkor nem almenu
	         */
	        if ( $last_group->depth == $this->GROUPS[$i]->depth )
	        {
		    echo '</li>';
	        }
	        elseif ( $last_group->depth < $this->GROUPS[$i]->depth )
	        {
	    	    echo '<ul>';

		    $submenu_num = 0;
	        }
	        elseif ( $last_group->depth > $this->GROUPS[$i]->depth )
	        {
	    	    echo str_repeat('</li></ul></li>', ($last_group->depth - $this->GROUPS[$i]->depth));
	        }
	    }

	    echo '<li class="' . ($i == 0 ? 'first ' : (($i + 1) == $c ? 'last ' : '')) . ($this->GROUPS[$i]->depth > 1 && $submenu_num++ % 2 == 0 ? 'clearer ' : '') . '"><a href="' . $this->GROUPS[$i]->get_url() . '">' . $this->GROUPS[$i]->name;

	    if ( $this->GROUPS[$i]->depth > 1 && strlen(trim($this->GROUPS[$i]->description)) )
	    {
		      //echo '<div class="description">' . $this->GROUPS[$i]->description . '</div>';
	    }

	    echo '</a>';

	    /**
	     * az utolso menut lezarjuk
	     * es a gyerekeit is ha vannak
	     */
	    if ( ($i + 1) == $c )
	    {
		if ( $last_group->depth > 1 )
		{
		    echo str_repeat('</li></ul>', ($last_group->depth - 1));
		}

		echo '</li>';
	    }
	}
    }

    echo '</ul>',
	 '</div></nav>',

	 '</div>', # header-container

	 '<div id="slideshow-container">',
	     '<div id="slideshow-left"><a href="#">&lt;</a></div>',

	     '<div id="slideshow-items">' . file_get_contents(dirname(__FILE__) . '/../../../../other/slideshow/slideshow.html') . '</div>',

	     '<div id="slideshow-right"><a href="#">&gt;</a></div>',
	 '</div>',

	 '</header>',

	 '<div id="container">';
