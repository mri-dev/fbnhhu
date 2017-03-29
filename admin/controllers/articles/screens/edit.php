<?php    
    echo '<section class="fullpage">';
    $this->header('Cikk szerkesztése', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->select_from_object('Fórum', 'forum_id', $this->FORUMS, 'id', 'name', 'noselect=true');
    $this->form->input_text('Cikk címe', 'title');
    $this->form->select('Hozzászólások', 'comments', $this->COMMENT_TYPES, 'noselect=true');
    $this->form->select('Láthatóság', 'public', $this->VISIBILITY, 'noselect=true');

    $this->form->editor('Előszó', 'lead');
    $this->form->editor('Cikk', 'content');
    $this->form->close('Mentés');
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
