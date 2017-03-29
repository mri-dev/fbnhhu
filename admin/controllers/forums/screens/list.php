<?php    
    echo '<section class="fullpage">';
    $this->header('Fórumok', null);
    echo '<div class="content-page">';

    $this->message('<a href="/admin/forums/add">' . icon('add', 'új fórum') . ' Új fórum létrehozása</a>');

    if ( $this->ITEMS )
    {
        $this->form->dl     = false;
        $this->form->nodiv  = true;

        $this->form->open();

	$this->table->set_class('table sortable');

	$this->table->set_header('Név', '');
	$this->table->extras = array(1 => array('width' => 25));

	foreach ( $this->ITEMS as $row )
	{
	    $this->table->add_row($row->name, icon('edit', 'szerkesztés', '/admin/forums/edit/' . $row->id));

	    $this->table->set_row_id('item' . $row->id);

	    $listorder[] = $row->id;
	}

	$this->table->flush_table();

        $this->form->input_hidden('listorder', $listorder ? implode(',', $listorder) : null);
        $this->form->close('Sorrend mentése');
    }
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
