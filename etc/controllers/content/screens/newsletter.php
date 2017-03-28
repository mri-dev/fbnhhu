<?php

    echo '<div class="page-width-holder"><div class="content-holder">';

    echo '<section id="forum-left">';

    $this->header($this->ITEM->title, null);
    echo '<div class="content-page newsletter-content">',
          $this->REPLACED_CONTENT,
         '</div></section>';
