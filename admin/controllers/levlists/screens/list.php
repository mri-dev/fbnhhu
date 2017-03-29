<?php
    echo '<section class="fullpage">';
    $this->header('Levelezőlisták', null);
    echo '<div class="content-page">';


    if ( $this->sm->check_right('levlists-add') )
    {
	$this->message('<a href="/admin/levlists/add">' . icon('add', 'hozzáadás') . ' Új levelezőlista létrehozása</a>');
    }

    $header = array('Levelezőlista', '');

    if ( $this->sm->check_right('levlists-full') )
    {
	$header[] = '';
    }

    $this->table->set_header($header);

    if ( $this->ITEMS )
    {
        $this->table->extras = array(
	    1 => array('width' => 25),
	    2 => array('width' => 25),
	);

	foreach ( $this->ITEMS as $item )
        {
	    $data = array($item->email, icon('edit', 'szerkesztés', '/admin/levlists/members/' . $item->id));

	    if ( $this->sm->check_right('levlists-full') )
	    {
		$data[] = icon('delete', 'törlés', '/admin/levlists/delete/' . $item->id, 'ajax-delete');
	    }

	    $this->table->add_row($data);
	}
    }

    $this->table->flush_table();

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
