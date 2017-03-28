<?php
    echo '<section id="forum-right">';

    if ( $this->CHILDRENLIST )
    {
	echo '<section id="last-articles" class="content-last-articles">';

	$this->header($this->PARENT->name);

        foreach ( $this->CHILDRENLIST as $item )
        {
            echo '<article class="latest-article-item">',
                 '<h3><a href="' . $item->get_url() . '">' . $item->name . '</a></h3>',

                 '<div>' . $item->description . '</div>',
                 '</article>';
        }

	echo '</section>';

    }
    elseif ( $this->LAST_ARTICLES )
    {
	echo '<section id="last-articles" class="content-last-articles">';

	$this->header('Legfrissebb tartalmak');

        foreach ( $this->LAST_ARTICLES as $article )
        {
            echo '<article class="latest-article-item">',
                 '<h3><a href="' . $article->get_url() . '">' . $article->title . '</a></h3>',
                 '<div>' . $article->get_lead() . '</div>',
                 '<header><span>Írta:</span> <a href="/admin/users?username=' . $article->owner->username . '">' . $article->owner->name . '</a> &bull; <span>Dátum:</span> ' . format_date($article->created_at) . '</header>',
                 '<div class="more"><a href="' . $article->get_url() . '">Tovább a cikkhez</a></div>',
                 '<div class="clearer"></div>',
                 '</article>';
        }

	echo '</section>';
    }

    echo '</section>';


   echo '</div></div>'; #./content-holder
