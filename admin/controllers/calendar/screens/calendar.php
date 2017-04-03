<?php
    echo '<div class="calendar-pager">',
	 '<a href="/admin/calendar/calendar?year=' . ($this->YEAR - 1) . '&month=' . $this->MONTH . '">&lt;&lt;</a> ' . $this->YEAR . ' <a href="/admin/calendar/calendar?year=' . ($this->YEAR + 1) . '&month=' . $this->MONTH . '" class="year-next">&gt;&gt;</a> ',
	 '<a href="/admin/calendar/calendar?year=' . ($this->MONTH == '01' ? $this->YEAR - 1 : $this->YEAR) . '&month=' . ($this->MONTH == '01' ? '12' : sprintf('%02d', ($this->MONTH - 1))) . '" class="month-prev">&lt;&lt;</a> ' . $this->MONTHNAMES[intval($this->MONTH)] . ' <a href="/admin/calendar/calendar?year=' . ($this->MONTH == 12 ? $this->YEAR + 1 : $this->YEAR) . '&month=' . ($this->MONTH == 12 ? '01' : sprintf('%02d', ($this->MONTH + 1))) . '">&gt;&gt;</a>',
	 '</div>';

    echo '<table class="calendar" cellspacing="3">',
	 '<tr><th>' . implode('</th><th>', $this->DAYNAMES) . '</th></tr>';

    $i = 0;

    foreach ( $this->DATES as $date )
    {
	if ( $i == 0 || $i % 7 == 0 )
	{
	    echo '<tr>';
	}

	$class = array();

	if ( isset($this->EVENTS[$date]) )
	{
	    $class[] = 'hasevent';
	}
	
	if ( $this->YEAR . '-' . $this->MONTH == date('Y-m', strtotime($date)) )
	{
	    $class[] = 'current';
	}

	echo '<td' . ($class ? ' class="' . implode(' ', $class) . '"' : '') . '>',
	     (isset($this->EVENTS[$date]) ? '<a href="/admin/calendar/eventlist?year=' . substr($date, 0, 4) . '&month=' . substr($date, 5, 2) . '&day=' . substr($date, 8, 2) . '">' . date('d', strtotime($date)) . '</a>' : date('j', strtotime($date))),
	     '</td>';

	$i++;

	if ( $i % 7 == 0 )
	{
	    echo '</tr>';
	}
    }

    echo '</table>';
