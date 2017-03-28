<?php

class index_controller {
    private $scr;

    public $news_action = null;

    function __construct ()
    {
	$this->scr	= new screen(__FILE__);

	$this->forumlist = new forumlist();
	$this->articlelist = new articlelist();
  $this->grouplist = new grouplist();

        $this->comment_types    = get_config('ARTICLES::COMMENT_TYPES');
        $this->visibility       = get_config('ARTICLES::VISIBILITY');

        $this->scr->register_var('COMMENT_TYPES', $this->comment_types);
        $this->scr->register_Var('VISIBILITY', $this->visibility);
    }

    function display ($params = null)
    {
	parse_str($params);

	if ( !isset($action) )
	{
	    $action = $_REQUEST['_ACTION'];
	}

	switch ( $action )
	{
	    case 'signup_fbnh' :
		if ( !$this->scr->sm->is_logged() )
		{
		    display_controller('error', 'action=404&error=Az oldal megtekintéséhez bejelentkezés szükséges.');

		    break;
		}

		$_PROGRAMS = array(1 => 'Konferencia', 'Családi hétvége (Szombat)', 'Családi hétvége (Vasárnap)');

		$this->scr->register_var('PROGRAMS', $_PROGRAMS);

		if ( isset($_REQUEST['submit-button']) )
		{
		    if ( !empty($_POST['c_name'])
		    &&   !empty($_POST['c_phone'])
		    &&   !empty($_POST['c_email'])
		    &&   !empty($_POST['names'][0])
		    &&   !empty($_POST['acc_name'])
		    &&   !empty($_POST['acc_address']) )
		    {
			$conf_members = 0;

			$vals = array(
			    'type'		=> 'KONF1',
			    'contact_name'	=> $_POST['c_name'],
			    'contact_phone'	=> $_POST['c_phone'],
			    'contact_email'	=> $_POST['c_email'],
			    'comment'		=> $_POST['comment'],
			    'acc_name'		=> $_POST['acc_name'],
			    'acc_address'	=> $_POST['acc_address'],
			    'user_id'		=> $this->scr->sm->uid,
			    'ip'		=> $_SERVER['REMOTE_ADDR'],
			);

			$query = new query();
			$query->insert('signup', $vals);

			if ( $query->insert_row() )
			{
			    $signup_id = $query->insert_id;

			    foreach ( $_POST['names'] as $id => $name )
			    {
				if ( empty($name) ) continue;

				$programs	= array();
				$members[$name]	= array();

				reset($_POST['program']);

				foreach ( $_POST['program'] as $program_id => $vals )
				{
				    if ( $vals[$id] == 1 )
				    {
					$programs[] = $program_id;

					$members[$name][] = $_PROGRAMS[$program_id];
				    }
				}

				if ( $_POST['program'][1][$id] == 1 )
				{
				    $conf_members += 1;
				}

				$vals = array(
				    'signup_id'	=> $signup_id,
				    'name'	=> $name,
				    'programs'	=> implode(',', $programs),
				);

				$query = new query();
				$query->insert('signup_members', $vals)->insert_row();
			    }

			    $body = '<h1 style="font: italic normal 20px/25px Georgia;">Kedves ' . $_POST['c_name'] . '!</h1>'

				  . '<p>Sikeresen regisztráltál a Felelős Családi Vállalkozások IV. konferenciájára!</p>'

				  . '<p>A konferenciáról minden szükséges információt a <a href="http://fbn-h.hu/konferencia">http://fbn-h.hu/konferencia</a> oldalon találsz.</p>'

				  . '<h2 style="font: italic normal 17px/20px Georgia;">Jelentkezésed adatai:</h2>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">KAPCSOLATTARTÓI ADATOK</h3>'
				  . '<ul>'
				  . '<li>Név: ' . $_POST['c_name'] . '</li>'
				  . '<li>Telefonszám: ' . $_POST['c_phone'] . '</li>'
				  . '<li>Email: ' . $_POST['c_email'] . '</li>'
				  . '</ul>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">RÉSZTVEVŐI ADATOK</h3>';

			    foreach ( $members as $name => $programlist )
			    {
				$body .= '<ul>'
				      . '<li>Név: ' . $name . '</li>'
				      . '<li>Programok: ' . implode(', ', $programlist) . '</li>'
				      . '</ul>';
			    }


			    $body .= '<h3 style="font: italic normal 15px/20px Georgia;">SZÁMLÁZÁSI ADATOK</h3>'
				  . '<ul>'
				  . '<li>Név: ' . $_POST['acc_name'] . '</li>'
				  . '<li>Cím: ' . $_POST['acc_address'] . '</li>'
				  . '</ul>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">SZÁMLÁZÁSI ADATOK</h3>'
				  . '<p>Egyéb információk: ' . htmlspecialchars($_REQUEST['comment']) . '</p>'

				  . '<h2 style="font: italic normal 17px/20px Georgia;">A részvételi díjak teljes összege: ' . max($conf_members * 27500 - 27500, 0) . ' Ft + ÁFA.</h2>'

				  . 'Jelentkezésed visszaigazolásáról hamarosan értesítünk az általad megadott elérhetőségeken (telefon vagy email). Ha bármilyen kérdésed, problémád merülne fel, nyugodtan írj emailt a programok@fbn-h.hu címre.'

				  . '<p>Hamarosan találkozunk,<br />'
				  . '<b>FBN-H</b></p>';

			    htmlmail($_POST['c_email'], 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);
			    htmlmail('programok@fbn-h.hu', 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);
			    htmlmail('agoston@11r.hu', 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);

			    $insert = true;
			}

		    }
		}

		if ( $insert )
		{
		    $this->scr->set_screen('SIGNUP_SUCCESS');
		}
		else
		{
		    $this->scr->set_screen('SIGNUP_FBNH');
		}

		break;

	    case 'signup' :
		if ( isset($_REQUEST['submit-button']) )
		{
		    if ( !empty($_POST['c_name'])
		    &&   !empty($_POST['c_phone'])
		    &&   !empty($_POST['c_email'])
		    &&   !empty($_POST['member_name'])
		    &&   !empty($_POST['names'][0])
		    &&   !empty($_POST['acc_name'])
		    &&   !empty($_POST['acc_address']) )
		    {
			$members = array();

			$vals = array(
			    'type'		=> 'KONF2',
			    'contact_name'	=> $_POST['c_name'],
			    'contact_phone'	=> $_POST['c_phone'],
			    'contact_email'	=> $_POST['c_email'],
			    'member_name'	=> $_POST['member_name'],
			    'comment'		=> $_POST['comment'],
			    'acc_name'		=> $_POST['acc_name'],
			    'acc_address'	=> $_POST['acc_address'],
			    'user_id'		=> $this->scr->sm->uid,
			    'ip'		=> $_SERVER['REMOTE_ADDR'],
			);

			$query = new query();
			$query->insert('signup', $vals);

			if ( $query->insert_row() )
			{
			    $signup_id = $query->insert_id;

			    foreach ( $_POST['names'] as $id => $name )
			    {
				if ( empty($name) ) continue;

				$members[] = $name;

				$vals = array(
				    'signup_id'	=> $signup_id,
				    'name'	=> $name,
				);

				$query = new query();
				$query->insert('signup_members', $vals)->insert_row();
			    }

			    $body = '<h1 style="font: italic normal 20px/25px Georgia;">Kedves ' . $_POST['c_name'] . '!</h1>'

				  . '<p>Sikeresen regisztrált a Felelős Családi Vállalkozások IV. konferenciájára!</p>'

				  . '<p>A konferenciáról minden szükséges információt a <a href="http://fbn-h.hu/konferencia">http://fbn-h.hu/konferencia</a> oldalon talál.</p>'

				  . '<h2 style="font: italic normal 17px/20px Georgia;">Jelentkezése adatai:</h2>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">KAPCSOLATTARTÓI ADATOK</h3>'
				  . '<ul>'
				  . '<li>Név: ' . $_POST['c_name'] . '</li>'
				  . '<li>Telefonszám: ' . $_POST['c_phone'] . '</li>'
				  . '<li>Email: ' . $_POST['c_email'] . '</li>'
				  . '<li>Meghívó személy vagy szervezet: ' . $_POST['member_name'] . '</li>'
				  . '</ul>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">RÉSZTVEVŐI ADATOK</h3>'
				  . '<ul>';

			    foreach ( $members as $name )
			    {
				$body .= '<li>Név: ' . $name . '</li>';
			    }

			    $body .= '</ul>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">SZÁMLÁZÁSI ADATOK</h3>'
				  . '<ul>'
				  . '<li>Név: ' . $_POST['acc_name'] . '</li>'
				  . '<li>Cím: ' . $_POST['acc_address'] . '</li>'
				  . '</ul>'

				  . '<h3 style="font: italic normal 15px/20px Georgia;">SZÁMLÁZÁSI ADATOK</h3>'
				  . '<p>Egyéb információk: ' . htmlspecialchars($_REQUEST['comment']) . '</p>'

				  . '<h2 style="font: italic normal 17px/20px Georgia;">A részvételi díjak teljes összege: ' . max(count($members) * 55000, 0) . ' Ft + ÁFA.</h2>'

				  . 'Jelentkezése visszaigazolásáról hamarosan értesítjük a megadott elérhetőségeken (telefon vagy email). Ha bármilyen kérdése, problémája merülne fel, írjon emailt a programok@fbn-h.hu címre.'

				  . '<p>Hamarosan találkozunk,<br />'
				  . '<b>FBN-H</b></p>';

			    htmlmail($_POST['c_email'], 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);
			    htmlmail('programok@fbn-h.hu', 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);
			    htmlmail('agoston@11r.hu', 'Jelentkezés a Felelős Családi Vállalkozások IV. konferenciájára', $body);

			    $insert = true;
			}

		    }
		}

		if ( $insert )
		{
		    $this->scr->set_screen('SIGNUP_SUCCESS');
		}
		else
		{
		    $this->scr->set_screen('SIGNUP');
		}

		break;

	    case 'logout' :
		if ( $this->scr->sm->is_logged() )
		{
		    $this->scr->sm->logout();
		}

		redirect::location('/');

		break;

	    default :
		// a hirek ne jelenjen meg a fooldalon
		$this->forumlist->filter = new filter('id <>', 13);

		$items		= $this->forumlist->get_list();
		$articles	= $this->articlelist->get_last_articles($this->scr->sm->user);

    define('ISHOME', true);
    $this->scr->register_var('ITEMS', $items);
		$this->scr->register_var('LAST_ARTICLES', $articles);
    $this->scr->register_var('FBNHGROUP', $this->grouplist->get_tree(15));
		$this->scr->set_screen('HOME');
		break;
	}
    }
}
