<?php
    echo '<section class="fullpage">';
    $this->header('Fórum szerkesztése', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Megnevezés', 'name');
    $this->form->select_from_object('Levelezőlista', 'levlist_id', $this->LEVLISTS, 'id', 'name');
    $this->form->textarea('Leírás', 'description');
    $this->form->close('Mentés');

    if ( $this->USERS )
    {
	$this->header('Fórum vezetői');

	$this->form->open();
	$this->form->select_from_object('Felhasználó', 'user_id', $this->USERS, 'id', 'name');
	$this->form->close('Hozzáadás', 'button_name=add_owner');

	$items = $this->ITEM->get_owners();

	if ( $items )
	{
	    $this->table->set_header('Név', '');

	    foreach ( $items as $row )
	    {
		$this->table->add_row($row->name, icon('delete', 'Eltávolitás a listából', '/admin/forums/edit/' . $this->ITEM->id . '?remove_owner=' . $row->id, 'ajax-delete'));
	    }

	    $this->table->flush_table();
	}
    }
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
