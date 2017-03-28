<?php
    echo '<section class="list-right" id="forum-right">',
    '<section>',
    '<div class="forum-lead">',
	 '<h3 class="first">Az archívumról</h3>',
	 '<div class="description">' . $this->GROUP->description . '</div>',
   '</div>';

    if ( $this->LAST_ITEMS )
    {
    	echo '<section id="last-articles">';

    	$this->header('Legutóbbi hírleveleink');

            foreach ( $this->LAST_ITEMS as $item )
            {
                echo '<article class="latest-article-item">',
                     '<h3><a href="' . $this->GROUP->fix_url . '/' . $item->get_url() . '">' . $item->title . '</a></h3>',
                     '<header><span>Írta:</span> <a href="/admin/users?username=' . $item->user->username . '">' . $item->user->name . '</a> &bull; <span>Dátum:</span> ' . format_date($item->approved) . '</header>',
                     '</article>';
            }

    	echo '</section>';
    }

    echo '</section></section>',
	 '<div class="clearer"></div>';
