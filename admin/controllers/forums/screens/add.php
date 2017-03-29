<?php
    echo '<section class="fullpage">';
    $this->header('Új fórum létrehozása', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Megnevezés', 'name');
    $this->form->select_from_object('Levelezőlista', 'levlist_id', $this->LEVLISTS, 'id', 'name');
    $this->form->textarea('Leírás', 'description');
    $this->form->close('Mentés');
    
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
