<?php
    echo '<div class="page-width-holder"><div class="content-holder">';

    echo '<section id="forum-left">',
    $this->header($this->GROUP->name, null),
    '<div class="content-page">',
    '<div id="editor_leader" class="bold">' . $this->GROUP->description . '</div>',
    '<div id="editor_content">' . $this->GROUP->content . '<div class="clearer"></div></div>';

    if ( $this->DOCUMENTS )
    {
	     $this->header('Dokumentumok', null, 'dotted-header-double');

        echo '<div class="dotted-header-bottom"></div>',
        	     '<ul id="article-documents">';

      	foreach ( $this->DOCUMENTS as $document )
      	{
      	    echo '<li><a href="' . $document['file'] . '" target="new">' . $document['title'] . '</a></li>';
      	}

    	  echo '</ul>';
    }

    echo '</div></section>';
