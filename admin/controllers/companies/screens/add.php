<?php
    echo '<section class="fullpage">';
    $this->header('Új cégprofil', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Megnevezés', 'name');
    $this->form->input_text('URL', 'comp_url');
    $this->form->input_text('Céges kapcsolattartó <a href="#" class="tooltip" title="Mely céges kapcsolattartó (aszisztens, menedzser) felelős az egyesülettel kapcsolatos időpont egyeztetésekért, egyéb marketing típusú koordinációért?<br /><br />Kérjük a kapcsolattartó elérhetőségeit is tüntesse fel.">(mi ez?)</a>', 'contact');
    $this->form->input_text('Kapcsolattartó e-mail címe', 'contact_phone');
    $this->form->input_text('Kapcsolattartó telefonszáma', 'contact_email');

    $this->form->input_text('Foglalkoztatottak létszáma (fő)', 'employees');
    $this->form->input_text('Éves nettó árbevétel (Ft)', 'revenue');
    $this->form->input_text('Céges image film (link)', 'youtube_url');
/*
    for ( $i = (date('Y') - 1); $i > (date('Y') - 4); $i-- )
    {
        $this->form->input_text('Nettó éves árbevétel forintban (' . $i . ')', 'single_revenues[' . $i . ']', 'class=revenues-value');
    }

    $this->form->input_text('Csoportszintű éves árbevétel forintban (' . (date('Y') - 1) . ') <a href="#" title="A család tulajdonlási körébe tartozó cégek összesített, nettó, utolsó évi árbevétele." class="tooltip">(mi ez?)</a>', 'group_revenues[' . (date('Y') - 1) . ']', 'class=revenues-value');
*/
    $this->form->editor('Bemutatkozás', 'description');
    $this->form->close('Mentés');

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
