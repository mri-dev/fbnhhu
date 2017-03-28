<?php

    echo '<div class="page-width-holder"><div class="content-holder">';

    echo '<section id="forum-left">';
    echo  $this->header('<a href="' . $this->FORUM->get_url() . '">' . $this->FORUM->name . '</a>', null);
    echo '<div class="content-page">';


    if ( $this->ITEMS )
    {
	foreach ( $this->ITEMS as $item )
	{
	    echo '<article class="item">',
		 '<h3><a href="' . $item->get_url() . '">' . $item->title . '</a></h3>',
		 '<div class="content">' . $item->get_lead() . '</div>',
     '<header><span>Írta:</span> <a href="/admin/users?username=' . $item->owner->username . '">' . $item->owner->name . '</a> &bull; <span>Dátum:</span> ' . $item->created_at . ($this->sm->is_logged() ? ' | <span>Típus:</span> ' . $this->VISIBILITY[$item->public] . ' &bull; <span>Hozzászólások:</span> ' . $this->COMMENT_TYPES[$item->comments] : '') . '</header>',
		 '<a href="' . $item->get_url() . '" class="more">Tovább a cikkhez</a>',
     '<div class="clearer"></div>',
     '</article>';
	}

	echo $this->table->generate_pager();
    }
    else
    {
	$this->message('Nem található egy cikk sem ebben a fórumban.', 'error');
    }

    echo '</div></section>';
