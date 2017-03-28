<?php
    $owners = $this->FORUM->get_owners();

    echo '<section id="forum-right">',
    '<section>',
    '<div class="forum-lead">',
	  '<h3 class="first">A fórumról</h3>',
	  '<div class="description">' . $this->FORUM->description . '</div>',
    '</div>';

    if ( $owners )
    {
    	echo '<h3>A(z) ' . $this->FORUM->name . ' vezetői</h3>',
    	     '<ul>';

    	foreach ( $owners as $owner )
    	{
    	    echo '<li><a href="/admin/users?username=' . $owner->username . '">' . $owner->name . '</a></li>';
    	}

    	echo '</ul>';
        }

        if ( $this->sm->is_logged()
        &&   $this->FORUM_LIST )
        {
            echo '<h3>Levelezőlista</h3>',
    	     '<div>' . $this->FORUM_LIST->email . ' &bull; <a href="' . $this->FORUM->get_url() . ($this->FORUM_LIST->is_member($this->sm->uid) ? '?unsubscribe=1">Leiratkozás' : '?subscribe=1">Feliratkozás') . '</a>';

            echo '</div>';

            if ( $this->FORUM_LIST_MEMBERS )
            {
    	    echo '<h3>A fórum tagjai</h3>',
    		 '<ul>';

    	    foreach ( $this->FORUM_LIST_MEMBERS as $item )
    	    {
    	        echo '<li><a href="/admin/users?username=' . $item->username . '">' . $item->name . '</a></li>';
    	    }

    	    echo '</ul>';
    	}
    }

    if ( $this->LAST_ARTICLES )
    {
    	echo '<section id="last-articles">';
    	$this->header('Legfrissebb tartalmak');

      foreach ( $this->LAST_ARTICLES as $article )
      {
          echo '<article class="latest-article-item">',
               '<h3><a href="' . $article->get_url() . '">' . $article->title . '</a></h3>',
               '<div>' . $article->get_lead() . '</div>',
               '<header><span>Írta:</span> <a href="/admin/users?username=' . $article->owner->username . '">' . $article->owner->name . '</a> &bull; <span>Dátum:</span> ' . format_date($article->created_at) . '</header>',
               '<div class="more"><a href="' . $article->get_url() . '">Tovább a cikkhez</a></div>',
               '<div class="clearer"></div></article>';
      }

    	echo '</section>';
    }

    echo '</section></section><div class="clearer"></div>';
    echo '</div></div>'; #./content-holder
