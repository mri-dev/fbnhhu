<?php
    echo '<section class="fullpage">';
    $this->header('Új kategória', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Kategória név', 'name');

    $this->form->input_text('Fix URL', 'fix_url');
    $this->form->checkbox('Publikus', 'public');

    $this->form->textarea('Rövid leírás', 'description');
    $this->form->editor('Tartalom', 'content');

    $this->form->close('Mentés');

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
