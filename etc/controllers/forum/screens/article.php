<?php

    echo '<div class="page-width-holder"><div class="content-holder">';

    echo '<section id="forum-left">';

    if($this->sm->is_logged()) {

      $after_action = ' &bull; <span>Típus:</span> ' . $this->VISIBILITY[$this->ITEM->public] . ' &bull; <span>Hozzászólások:</span> ' . $this->COMMENT_TYPES[$this->ITEM->comments];
    }

    echo $this->header( $this->ITEM->title .'<div class="subline"><span class="forum">Fórum: <a href="' . $this->ITEM->forum->get_url() . '">' . $this->ITEM->forum->name . '</a></span> &nbsp;&nbsp; <span class="header"><span>Írta:</span> <a href="/admin/users?username=' . $this->ITEM->owner->username . '">' . $this->ITEM->owner->name . '</a> &bull; <span>Dátum:</span> '.format_date($this->ITEM->created_at, false, true). $after_action .' </span></div>', null);

    echo '<div class="content-page">',
	 '<article class="article-item">',
	 '<div id="editor_lead">' . $this->ITEM->get_lead() . '</div>',
	 '<div id="editor_content">' . $this->ITEM->content . '<div class="clearer"></div></div>',
	 '</article>';

    if ( $this->ITEM->public == 1 )
    {
    	echo '<div id="share" class="floatright"></div><div class="clearer"></div>',

    	     '<script>',
    	     '$(\'#share\').sharrre({ ',
    	     'share: { googlePlus: true, facebook: true, twitter: true }, ',
    	     'enableHover: false, ',
    	     'enableCounter: false, ',
    	     'buttons: { googlePlus: {size: \'tall\'}, facebook: {layout: \'box_count\'}, twitter: {count: \'vertical\'} }, ',
    	     'url: \'http://' . get_config('DEFAULT::URL') . $_SERVER['REQUEST_URI'] . '\'}); ',
    	     '</script>';
    }

    /**
     * ha nem tiltottak a hozzaszolasok
     */
    if ( $this->ITEM->comments !== 3)
    {
	/**
	 * ha a kommentek publikusak
	 * vagy rejtettek es be van jelentkezve
	 */
	if ( $this->ITEM->comments == 1 || ($this->ITEM->comments == 2 && $this->sm->is_logged()) )
	{
	    echo '<div id="comments-container">',
		 '<h3 class="header" id="hozzaszolasok">Hozzászólások</h3>';

	    /**
	     * hozzaszolhatunk ha be vagyunk jelentkezve
	     */
	    if ( $this->sm->is_logged() )
	    {
    	    $this->form->dl = false;

	        $this->form->open();
	        $this->form->textarea(null, 'comment');
    	    $this->form->close('Hozzászólás');
	    }

	    if ( $this->COMMENTS )
	    {
		foreach ( $this->COMMENTS as $comment )
		{
		    echo '<div class="article-comment" id="comment' . $comment->id . '">',
			 '<h3><a href="/admin/users?username=' . $comment->user->username . '">' . $comment->user->name . '</a></h3>',
			 '<header>Dátum: <strong>' . $comment->created_at . '</strong> &middot; <a href="' . $_SERVER['REQUEST_URI'] . '#comment' . $comment->id . '">Direkt hivatkozás a hozzászóláshoz</a>';

		    if ( $this->IS_OWNER )
		    {
			echo ' &middot; Adminisztráció: ',

			     '<a href="' . $this->ITEM->get_url() . '?delete_comment=' . $comment->id . '">törlés</a>';
		    }

		    echo '</header>',
			 '<div class="comment-content">' . $this->ITEM->comment_html($comment->content) . '</div>',
			 '</div>';
		}
	    }

    	    echo '</div>';
	}
    }
    echo '</div></section>'; # forum-left
