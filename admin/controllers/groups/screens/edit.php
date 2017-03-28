<?php
    echo '<section class="fullpage">';
    $this->header('Szerkesztés', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Kategória név', 'name');

    $this->form->input_text('Fix URL', 'fix_url');
    $this->form->checkbox('Publikus', 'public');

    $this->form->textarea('Rövid leírás', 'description');
    $this->form->editor('Tartalom', 'content');

    $this->form->close('Mentés');

    $this->header('Dokumentumok', 'documents');

    $this->message('Engedélyezett kiterjesztések:  (.' . implode(', .', $this->EXTENSIONS) . ')');
    $this->form->open('comp-files');
    $this->form->input_text('Megnevezés', 'docname');
    $this->form->input_file('Fájl', 'docfile');
    $this->form->close('Feltöltés', 'button_name=upload_file');

    if ( $this->DOCUMENTS )
    {
        $this->header('Feltöltött dokumentumok');

        $this->table->set_header('Dokumentum', '');
        $this->table->extras = array(1 => array('width' => '25px'));

        foreach ( $this->DOCUMENTS as $id => $document )
        {
            $this->table->add_row('<a href="' . $document['file'] . '">' . $document['title'] . '</a>', icon('delete', 'törlés', '/admin/groups/edit/' . $this->ITEM->id . '?delete_file=' . $id, 'ajax-delete'));
        }

        $this->table->flush_table();
    }


    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
