<?php

class calendar_controller 
{
    private $scr;

    public $year;
    public $month;
    public $day;
    public $events 	= array();
    public $first_event = null;

    function __construct () 
    {
	$this->scr = new screen(__FILE__);

	$this->eventlist	= new eventlist();
	$this->attendlist	= new event_attendlist();

	$this->daynames		= get_config('DEFAULT::SDAYNAMES');
	$this->monthnames	= get_config('MONTHS');

	$this->scr->register_var('TYPES', get_config('CALENDAR::TYPES'));
	$this->scr->register_var('DAYNAMES', $this->daynames);
	$this->scr->register_var('MONTHNAMES', $this->monthnames);

	/**
	 * beallitjuk a kivalaszott datumot
	 * ha get-bol jon, akkor azt vesszuk alapul
	 */
	$this->year = date('Y'); $this->month = date('m'); $this->day = date('d');

	if ( isset($_REQUEST['year']) )
	{
	    $this->year = $_REQUEST['year'];
	}

	if ( isset($_REQUEST['month']) )
	{
	    $this->month = $_REQUEST['month'];
	}

	if ( isset($_REQUEST['day']) )
	{
	    $this->day = $_REQUEST['day'];
	}

	$this->register_events();

	/**
	 * ha nincsen a mai napra esemeny, akkor a legelsohoz ugrunk
	 */
/*
	if (!isset($_REQUEST['year'])) {
	    if ( !isset($this->events[date('Y-m-d')]) && date('Y-m-d') == $this->year . '-' . $this->month . '-' . $this->day && $this->first_event )
	    {
	        $this->year		= substr($this->first_event->date, 0, 4);
	        $this->month	= substr($this->first_event->date, 5, 2);
	        $this->day 		= substr($this->first_event->date, 8, 2);
	    }
	}
*/
    }

    function display ($mod = null) 
    {
	parse_str($mod);

	if ( $action )
	{
	    $_REQUEST['_ACTION'] = mb_strtolower($action);
	}

	switch ( $_REQUEST['_ACTION'] ) 
	{
	    case 'calendar' :
		$firstday	= date('Y-m-01', strtotime($this->year . '-' . $this->month));

		$lastday	= date('Y-m-t', strtotime($firstday));
		$weekofday	= date('N', strtotime($firstday));

		/**
		 * osszeszedjuk a naptarban feltuntetett datumokat
		 */
		for ( $i = (1 - $weekofday); $i < (36 - $weekofday); $i++ )
		{
		    $dates[] = date('Y-m-d', strtotime($firstday . '+' . $i . 'days'));
		}

		$this->scr->register_var('YEAR', $this->year);
		$this->scr->register_var('MONTH', $this->month);
		$this->scr->register_var('DATES', $dates);
		$this->scr->set_screen('CALENDAR');
		break;

	    case 'calendar_list' :
		$this->scr->register_var('DATE', date('Y-m-d', strtotime($this->year . '-' . $this->month . '-' . $this->day)));

		$this->eventlist->filter = new filter('CONCAT(date, \' \', time) >', date('Y-m-d H:i', strtotime('-6hours')));
		$this->eventlist->filter->where('deleted IS');

		$this->eventlist->set_limit(10);
		$this->eventlist->set_order('CONCAT(date, \' \', time)', false);

		$this->scr->register_var('NEXT_EVENTS', $this->eventlist->get_list());
		$this->scr->set_screen('CALENDAR_LIST');
		break;

	    case 'attend' :
		$item = $this->eventlist->get($_REQUEST['id']);

		$return['ok'] = false;

		if ( ($item instanceof event) )
		{
		    if ( !($attend = $item->is_attended($this->scr->sm->user)) )
		    {
			$attend = new event_attend(null, $this->scr->sm->user, $item);

			if ( $attend->insert() )
		        {
			    $return['ok']	= true;
			    $return['name']	= '<a href="/admin/users?username=' . $attend->user->username . '">' . $attend->user->name . '</a>';
			}
		    }
		    else
		    {
			if ( $attend->delete() )
			{
			    $return['delete']	= true;
			    $return['ok']	= true;
			}
		    }

		    $return['attend_id']	= $attend->id;
		}
		
		echo json_encode($return);

		break;

	    case 'edit' :
		$item = $this->eventlist->get($_REQUEST['_ID']);

		if ( !($item instanceof event) || ($item->user->id !== $this->scr->sm->uid && !$this->scr->sm->check_right('calendar-full')) )
		{
		    display_controller('error', 'action=404&error=Az esemény nem található.');
		    
		    break;
		}

		if ( isset($_REQUEST['submit-button']) )
		{
		    $temp_item = new event($item->id, $this->scr->sm->user, $_REQUEST['type'], $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['location'], $_REQUEST['date'], $_REQUEST['time']);

		    if ( !$temp_item->update() )
		    {
			form_error($item->errors);
		    }
		    else
		    {
			redirect::location('/admin/calendar?year=' . substr($_REQUEST['date'], 0, 4) . '&month=' . substr($_REQUEST['date'], 5, 2) . '&day=' . substr($_REQUEST['date'], 8, 2));
		    }
		}
		else
		{
		    $_REQUEST['type']		= $item->type;
		    $_REQUEST['title']		= $item->title;
		    $_REQUEST['content']	= $item->content;
		    $_REQUEST['location']	= $item->location;
		    $_REQUEST['date']		= $item->date;
		    $_REQUEST['time']		= $item->time;
		}
		
		$this->scr->register_var('ITEM', $item);
		$this->scr->set_screen('EDIT');

		break;

	    case 'add' :
		if ( isset($_REQUEST['submit-button']) )
		{
		    $item = new event(null, $this->scr->sm->user, $_REQUEST['type'], $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['location'], $_REQUEST['date'], $_REQUEST['time']);
		    
		    if ( !$item->insert() )
		    {
			form_error($item->errors);
		    }
		    else
		    {
			redirect::location('/admin/calendar');
		    }
		}

		$this->scr->set_screen('ADD');
		break;

	    case 'eventlist' :
		$date = date('Y-m-d', strtotime($this->year . '-' . $this->month . '-' . $this->day));

		if ( strtotime($date) <= 0 )
		{
		    display_controller('error', 'action=404&error=Hibás dátum.');
		    
		    break;
		}

		$this->scr->register_var('DATE', $date);
		$this->scr->set_screen('EVENTLIST');
		break;

	    default : 
		if ( $_REQUEST['delete'] > 0 )
		{
		    $item = $this->eventlist->get($_REQUEST['delete']);
		    
		    if ( !($item instanceof event) || ($item->user->id != $this->scr->sm->uid && !$this->scr->sm->check_right('calendar-full')) )
		    {
			display_controller('Hiba történt törlés közben.');
			
			break;
		    }
		    else
		    {
			$item->delete();
			
			$this->register_events();
		    }
		}

		$this->scr->set_screen('LIST');
		break;
	}
    }

    function register_events ()
    {
	$this->eventlist->filter = new filter('deleted IS');

	$this->eventlist->set_order('CONCAT(date, \' \', time)');
	$this->eventlist->set_limit(0);

	$items = $this->eventlist->get_list();

	if ( $items )
	{
	    foreach ( $items as $item )
	    {
	        $this->events[$item->date][] = $item;

		if ( $item->date >= date('Y-m-d') && !$this->first_event )
		{
		    $this->first_event = $item;
		}
	    }
	}

	$this->scr->register_var('EVENTS', $this->events);
    }
}