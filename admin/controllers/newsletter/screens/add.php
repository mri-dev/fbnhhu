<?php
    echo '<section class="fullpage">';
    $this->header('Hírlevél hozzáadása', null);
    echo '<div class="content-page">';

    $this->form->open('newsletter');
    $this->form->select_from_object('Levelezőlista', 'levlist_id', $this->FORUM_LISTS, 'id', 'name', ($this->sm->check_right('newsletter-full') ? 'selectstr=Küldés az összes felhasználónak' : 'noselect=true'));
    $this->form->input_text('Hírlevél címe', 'title');
    $this->form->input_file('Csatolmány', 'attachments[]', 'clone=1');
    $this->form->close_dl();

/*
    echo '<h3 class="clearer">Csatolmányok</h3>',

	 '<ul id="attachments">';

    if ( $this->ATTACHMENTS )
    {
	foreach ( $this->ATTACHMENTS as $id => $file )
	{
	    echo '<li id="attachment' . $id . '">' . icon('cancel', 'törlés', '/admin/newsletter/edit/' . $this->ITEM->id . '?delete_file=' . $id, 'ajax-delete') . '<br />' . basename($file) . '</li>';
	}
    }

    echo '</ul>',
	 '<div class="clearer"></div>';
*/

    $this->form->open_dl();
    $this->form->text('Teljes név: %fullname%, keresztnév: %firstname%', 'Használható változók');
    $this->form->editor('Hírlevél szövege', 'content');
    $this->form->close_dl();

    $this->form->dl = false;
    $this->form->nodiv = true;

    $this->form->input_hidden('articlelist');

    echo '<div class="newsletter-items" id="newsletter-articles">',

	 '<div class="left">',

	 '<h2 class="title">Csatolható cikkek</h2>',
	 '<ul class="connected-articles">';

    foreach ( $this->ARTICLES as $item )
    {
	if ( !in_array($item->id, (array) explode(',', $_REQUEST['articlelist'])) )
	{
	    echo '<li id="item' . $item->id . '">',
		 '<h3>' . $item->title . '</h3>',
	         '<div class="item-content">' . $item->get_lead() . '</div>',
	         '</li>';
	}
    }

    echo '</ul>',
	 '</div>',

	 '<div class="right">',

	 '<div class="selected-items">',
	 '<div class="selected-items-content">',
	 '<h2 class="title">Hírlevél előnézet</h2>',

	 '<ul class="connected-articles">';

    foreach ( $this->ARTICLES as $item )
    {
	if ( in_array($item->id, (array) explode(',', $_REQUEST['articlelist'])) )
	{
	    echo '<li id="item' . $item->id . '">',
		 '<h3>' . $item->title . '</h3>',
	         '<div class="item-content">' . $item->get_lead() . '</div>',
	         '</li>';
	}
    }


    echo '</ul>',
	 '<div class="clearer"></div>',
	 '</div>', # selected-items-content
	 '</div>', # selected-items
	 '</div>', # right

	 '<div class="clearer"></div>',
	 '</div>'; # newsletter-items

    $this->form->input_hidden('eventlist');

    echo '<div class="newsletter-items" id="newsletter-events">',

	 '<div class="left">',

	 '<h2 class="title">Csatolható események</h2>',
	 '<ul class="connected-events">';

    foreach ( $this->EVENTS as $item )
    {
	if ( !in_array($item->id, (array) explode(',', $_REQUEST['eventlist'])) )
	{
	    echo '<li id="item' . $item->id . '">',
		 '<h3>' . $item->title . '</h3>',
	         '<div class="item-content">' . $item->location . '</div>',
	         '</li>';
	}
    }

    echo '</ul>',
	 '</div>',

	 '<div class="right">',

	 '<div class="selected-items">',
	 '<div class="selected-items-content">',
	 '<h2 class="title">Hírlevél előnézet</h2>',

	 '<ul class="connected-events">';

    foreach ( $this->EVENTS as $item )
    {
	if ( in_array($item->id, (array) explode(',', $_REQUEST['eventlist'])) )
	{
	    echo '<li id="item' . $item->id . '">',
		 '<h3>' . $item->title . '</h3>',
	         '<div class="item-content">' . $item->location . '</div>',
	         '</li>';
	}
    }

    echo '</ul>',
	 '<div class="clearer"></div>',
	 '</div>', # selected-items-content
	 '</div>', # selected-items
	 '</div>', # right

	 '<div class="clearer"></div>',
	 '</div>'; # newsletter-items

    $this->form->button('Mentés és ugrás a szerkesztésre');

    $this->form->close(false);

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
