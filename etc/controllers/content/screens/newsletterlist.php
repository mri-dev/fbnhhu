<?php
    echo '<div class="page-width-holder"><div class="content-holder">';

    echo '<section class="list-left" id="forum-left">';
    $this->header($this->GROUP->name, null);

    echo '<div class="content-page">';

    if ( $this->ITEMS )
    {
	foreach ( $this->ITEMS as $item )
	{
	    echo '<div class="userblock single">',
		 '<h2><a href="' . $this->GROUP->fix_url . '/' . $item->get_url() . '">' . $item->title . '</a></h2>',
		 '<header><span>Írta:</span> <a href="/admin/users?username=' . $item->user->username . '">' . $item->user->name . '</a> &bull; <span>Dátum:</span> ' . $item->approved . '</header>',
		 '</div>';
	}

	   echo $this->table->generate_pager();
    }
    else
    {
	      $this->message('Az archívumban nem találhatóak bejegyzések.', 'error');
    }

    echo '</div></section>';
