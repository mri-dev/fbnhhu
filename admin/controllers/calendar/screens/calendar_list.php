<?php
    $this->header('Közelgő események');

    echo '<div id="calendar-list">';

    if ( $this->NEXT_EVENTS )
    {
	foreach ( $this->NEXT_EVENTS as $item )
	{
	    echo '<div class="eventitem">',
		 '<h3><a href="/admin/calendar/eventlist?year=' . substr($item->date, 0, 4) . '&amp;month=' . substr($item->date, 5, 2) . '&amp;day=' . substr($item->date, 8, 2) . '">' . $item->title . '</a></h3>',

		 '<div class="information">',
                 '<p>dátum: <span class="bold">' . format_date($item->date . ' ' . $item->time, false, true) . '</span></p>',
                 '<p>helyszín: <span class="bold">' . $item->location . '</span></p>',
                 '<p>rendezvény jellege: <span class="bold">' . $this->TYPES[$item->type] . '</span></p>',
                 '<p>feltöltötte: <span class="bold">' . $item->user->name . '</span></p>',
		 '</div>',
		 '</div>';
	}
    }
    
    echo '</div>';
