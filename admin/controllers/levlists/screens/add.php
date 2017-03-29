<?php
    echo '<section class="fullpage">';
    $this->header('Új lista létrehozása', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Megnevezés', 'name');
    $this->form->input_text('Email (@fbn-h.hu előtti rész)', 'email');
    $this->form->close('Mentés');

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
