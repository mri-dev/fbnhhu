<?php
    echo '<section class="fullpage">';
    $this->header($this->ITEM->name . ' szerkesztése', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Megnevezés', 'name');
    $this->form->input_text('Email (@fbn-h.hu előtti rész)', 'email');
    $this->form->close();

    $this->header(urlfriendly($this->ITEM->name) . '@fbn-h.hu levelezőlista tagjainak szerkesztése', null, 'members-header');

    $this->form->open();
    $this->form->select_from_object('Családtag', 'user_id', $this->USERS, 'id', 'name');
    $this->form->close('Családtag felvétele a listára', 'button_name=add-member');

    if ( $this->LIST_MEMBERS )
    {
	$this->table->set_header('Név', '');

	$this->table->extras = array(
	    1 => array('width' => 25),
	);

	foreach ( $this->LIST_MEMBERS as $item )
	{
	    $this->table->add_row('<a href="/admin/users?username=' . $item->username . '">' . $item->name . '</a>', icon('delete', 'törlés', '/admin/levlists/members/' . $this->ITEM->id . '?delete=' . $item->id, 'ajax-delete'));
	}

	$this->table->flush_table();
    }
    else
    {
	$this->message('A listára nincs feliratkozva egyetlen felhasználó sem.', 'error');
    }
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
