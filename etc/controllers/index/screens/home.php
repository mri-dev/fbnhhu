<?php
  echo '<section id="nav-buttons"><div class="page-width-holder">';
      echo '<div class="holder">',
      '<h2 class="page_header">Az FBN-H-ról</h2>',
      '</div>';
  echo '</div></section>';

  echo '<section id="szalag"><div class="page-width-holder">';
      echo '<div class="holder">',
      '<div class="title">',get_config('SZALAG::TITLE'),'</div>',
      '<div class="subtitle">',get_config('SZALAG::SUBTITLE'),'</div>',
      '</div>';
  echo '</div></section>';

  echo '<section id="forum"><div class="page-width-holder">';
    $this->header('Fórumok');

    if ( $this->ITEMS )
    {
      echo '<div class="section-holder flex-grid forum-home-view grid-x5">';
    	foreach ( $this->ITEMS as $item )
    	{
    	    if ( $item->id == 15 )
    	    {
    		continue;
    	    }

    	    echo '<article class="forumbox' . ($i++ % 2 == 0 ? ' clearer' : '') . '">',
    		 '<h3><a href="' . $item->get_url() . '">' . $item->name . '</a></h3>',
    		 '<p>' . $item->description . '</p>',

    		 '<a href="' . $item->get_url() . '" class="more">tovább a cikkekhez</a>',
    	         '</article>';
    	}
      echo '</div>';
    }

    echo '</div><div class="clearer"></div>',
	 '</section>',

	 '<section id="news">',
	 '<div id="news-container" class="page-width-holder">',
	 '<div id="news-content">';
/*
    echo '<img src="/images/signup.jpg" alt="jelentkezés" usemap="#signupmap" /><br /><br />',
	 '<map name="signupmap">',
	 '<area shape="rect" coords="30,120,190,170" href="/konferencia_fbnh" alt="regisztrációs fbn-h tagoknak" />',
	 '<area shape="rect" coords="230,120,380,170" href="/konferencia" alt="regisztráció meghivottaknak" />',
	 '</map>';
*/
    $this->header('Friss tartalmak');

    if ( $this->LAST_ARTICLES )
    {
      echo '<div class="section-holder flex-grid post-list-default grid-x3">';
    	foreach ( $this->LAST_ARTICLES as $article )
    	{
    	    echo '<article class="latest-article-item"><div class="iwrapper">',
    		 '<h3><a href="' . $article->get_url() . '">' . $article->title . '</a></h3>',
    		 '<p>' . $article->get_lead(60) . '</p>',
         '<header><span>Írta:</span> <a href="/admin/users?username=' . $article->owner->username . '">' . $article->owner->name . '</a> &bull; <span>Dátum:</span> ' . format_date($article->created_at) . '</header>',
         '<div class="more"><a href="' . $article->get_url() . '">Részletek</a></div>',
    		 '<div class="clearer"></div></div></article>';
    	}
      echo '</div>';
    }

    echo '</div>',
	 '</div>',
	 '</section>';
