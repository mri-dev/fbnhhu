<?php
    echo '<section class="fullpage">';
    $this->header('Hírlevelek', null);
    echo '<div class="content-page">';

    $this->message('<a href="/admin/newsletter/add">' . icon('add', 'Új') . ' Új hírlevél létrehozása</a>');

    if ( $this->ITEMS )
    {
        $this->table->set_header('Hírlevél címe', 'Levelezőlista', 'Létrehozta', 'Létrehozva', 'Küldés kezdete és vége', 'Kiküldve', '');

	$this->table->extras = array(
	    6 => array('width' => 20),
	);

	foreach ( $this->ITEMS as $row )
	{
	    $this->table->add_row(
		$row->title,
		($row->levlist ? $row->levlist->name : 'Mindegyik'),
		$row->user->name,
		$row->created_at,
		($row->sendstart ? $row->sendstart : '-') . ($row->sendfinish ? ' - ' . $row->sendfinish : ''),
		($row->approved ? $row->approved : '-'),
		icon('edit', 'újraküldés', '/admin/newsletter/edit/' . $row->id));
	}

	$this->table->flush_table();
    }
    else
    {
	$this->message('Nincsenek listázható tételek.', 'error');
    }
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
