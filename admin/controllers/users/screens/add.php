<?php
    echo '<section class="fullpage">';
    $this->header('Felhasználó hozzáadása', null);
    echo '<div class="content-page">';

    $this->form->open();
    $this->form->input_text('Név', 'name');
    $this->form->input_text('Felhasználónév', 'username');
    $this->form->input_text('Email cím', 'email');
    $this->form->input_text('Telefonszám', 'phone');

    if ($this->sm->check_right('users-edit')) {
        $this->form->input_text('Születési dátum (év-hónap-nap)', 'birthdate');
    }

    $this->form->select('Státusz', 'generation', $this->GENERATIONS);
    $this->form->textarea('Bemutatkozás', 'description');
    $this->form->textarea('Gyermekeim neve és születési éve', 'children');
    //$this->form->textarea('Érdeklődési kör', 'interests');

    $this->form->select_from_object('Cég', 'company_id', $this->COMPS, 'id', 'name');

    $interests = get_config('INTERESTS');

    $this->header('Érdeklődési kör');
    echo '<p>Kérjük, jelöld meg, hogy az alábbi kategóriák közül mi érdekel Téged.</p>';

    foreach ($interests as $interest_id => $interest) {
    	$this->form->checkbox($interest, 'interests_categories[]', 'value=' . $interest_id . (in_array($interest_id, explode(',', $_REQUEST['interests_categories'])) ? '&checked=checked': ''));
      echo '<div class="clearer"></div>';
    }

    $this->form->input_text('Egyéb érdeklődési kör', 'interests');

    $this->form->close('Mentés');
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
