<?php
  echo '<section class="fullpage">';
    $this->header('Cégprofil szerkesztése', null);
    echo '<div class="content-page">';

      echo '<div id="companies">';

    $this->form->open('profil');
    $this->form->input_text('Megnevezés', 'name');

    if ( $this->sm->check_right('companies-full') )
    {
	$this->form->select_from_object('Adminisztrátor', 'owner_id', $this->USERS, 'id', 'name');
    }

    $this->form->input_text('URL', 'comp_url');

    $this->form->select_from_object('Family Business kapcsolattartó családtag <a href="#" title="Mely családtag felelős az egyesületti programokon részvételért, kapcsolattartásért és a családban a family business szakmai kérdések koordinációjáért? <br /><br />A céghez már hozzáadott családtagok közül választhat - ezt az oldal alján teheti meg." class="tooltip">(mi ez?)</a>', 'fb_contact', $this->MEMBERS, 'user->id', 'user->name');
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

    $this->header('Profilkép');
    $this->form->input_file('Kép (.jpg, .jpeg, .png)', 'picture');

    $this->form->text('<img src="' . $this->ITEM->get_image() . '" alt="' . $this->ITEM->name . '" id="profile_picture" />');

    $this->form->close('Mentés');

    $this->header('Dokumentumok');

    $this->message('Engedélyezett kiterjesztések:  (.' . implode(', .', $this->EXTENSIONS) . ')');
    $this->form->open('comp-files');
    $this->form->input_file('Dokumentum', 'compfile');
    $this->form->close('Feltöltés', 'button_name=upload_file');

    if ( $this->DOCUMENTS )
    {
	$this->header('Feltöltött dokumentumok');

	$this->table->set_header('Dokumentum');

	foreach ( $this->DOCUMENTS as $document )
	{
	    $this->table->add_row('<a href="' . $document . '">' . basename($document) . '</a>');
	}

	$this->table->flush_table();
    }

    $this->header('Cégtagok');

    $this->form->open('comp-member');
    $this->form->select_from_object('Név', 'user_id', $this->USERS, 'id', 'name');
    $this->form->close('Hozzáadás', 'button_name=add-member');

    $this->table->id = 'members';
    $this->table->set_header('Név', '', '');

    $this->table->extras = array(2 => array('width' => 25),);

    if ( $this->MEMBERS )
    {
	foreach ( $this->MEMBERS as $row )
	{
	    $this->table->add_row($row->user->name, '', icon('delete', 'Törlés', '/admin/companies/delete-member/' . $row->id, 'ajax-delete'));
	}
    }

    $this->table->flush_table();

    echo '</div>';

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
