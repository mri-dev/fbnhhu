<?php
    $this->header(format_date($this->DATE), null);

    echo '<div class="content-page">';
    echo '<div class="eventlist">';

    if ( $this->EVENTS[$this->DATE] )
    {
  	foreach ( $this->EVENTS[$this->DATE] as $item )
  	{
  	    $attends	= $item->get_attends();
  	    $attended	= $item->is_attended($this->sm->user);

  	    echo '<div class="eventitem">',
  		 '<h3>' . $item->title . ($item->user->id == $this->sm->uid || $this->sm->check_right('calendar-full') ? ' <a class="editing" href="/admin/calendar/edit/' . $item->id . '">szerkesztés</a> <a class="deleting" href="/admin/calendar?year=' . $_REQUEST['year'] . '&amp;month=' . $_REQUEST['month'] . '&amp;day=' . $_REQUEST['day'] . '&amp;delete=' . $item->id . '">törlés</a>' : '') . '</h3>',

  		 '<div class="information">',
  		 '<p>dátum: <span class="bold">' . format_date($item->date . ' ' . $item->time, false, true) . '</span></p>',
  		 '<p>helyszín: <span class="bold">' . $item->location . '</span></p>',
  		 '<p>rendezvény jellege: <span class="bold">' . $this->TYPES[$item->type] . '</span></p>',
  		 '<p>feltöltötte: <span class="bold">' . $item->user->name . '</span></p>',
  		 '</div>',

  		 '<h4>Esemény leírása</h4>',

  		 '<div class="content">' . $item->content . '</div>',

  		 '<div class="attends">',
  		 ($item->date < date('Y-m-d') ? '' : '<a href="/admin/calendar/attend?id=' . $item->id . '" class="attend">' . ($attended ? 'Mégsem tudok ott lenni az eseményen' : 'Jelentkezés az eseményre') . '</a>'),
  		 '<p>' . ($item->date < date('Y-m-d') ? 'Akik résztvettek az eseményen:' : 'Akik már jelentkeztek, hogy részt vesznek:') . '</p>',
  		 '<ul>';

  	    if ( $attends )
  	    {
      		foreach ( $attends as $i => $attend )
      		{
      		    echo '<li id="attend' . $attend->id . '" class="' . (intval($i / 2) % 2 == 0 ? 'odd' : 'even') . '"><a href="/admin/users?username=' . $attend->user->username . '">' . $attend->user->name . '</a></li>';
      		}
  	    }

  	    echo '</ul>',
  		 '<div class="clearer"></div>',
  		 '</div>',
  		 '</div>';
  	  }
    }
    else
    {
	     $this->message('Erre a napra nincs esemény.', 'error');
    }

     echo '</div>',
    '<div class="clearer"></div>';
    echo '</div>';
