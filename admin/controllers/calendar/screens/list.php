<?php
    echo '<section id="left" class="list-left calendar-left">';
    display_controller('calendar', 'action=EVENTLIST');
    echo '</section>',

	 '<section id="right" class="list-right">',
   '<section>',
  	 '<div id="news-container">',
    	 '<div id="news-content">';

        $this->header('Naptár nézet');

        echo '<div id="calendar-container">';

        echo '<div id="calendar-content">';
        display_controller('calendar', 'action=CALENDAR');
        echo '</div>';

        if ( $this->sm->check_right('calendar-full')
        ||   $this->sm->check_right('calender-add') )
        {
          echo '<div class="clearer"></div><br>';
    	    echo '<a class="creator" style="font-size: 0.9em;" href="/admin/calendar/add" class="add-event">Új esemény hozzáadása</a>';
        }

        echo '<div id="calendar-list-container">';

        display_controller('calendar', 'action=CALENDAR_LIST');

        echo '</div>',
    	 '</div>',

    	 '</div>',
  	 '</div>',
   '</section>',
	 '</section>';
