<?php


    echo '<section class="fullpage">';
    $this->header('A(z) "' . $this->ITEM->username . '" profil szerkesztése' . ($this->sm->check_right('users-rights') ? ' <br><a class="editing" href="/admin/users/rights/' . $this->ITEM->id . '">jogok szerkesztése</a>': ''), null);
    echo '<div class="content-page">';

    if ( $this->PASSWD_UPDATED )
    {
	$this->message('A felhasználó jelszava sikeresen megváltozott.', 'success');
    }

    $this->form->open('profil');

    $this->form->input_text('Név', 'name');
    $this->form->input_text('Felhasználónév', 'username');
    $this->form->input_text('Email cím', 'email');
    $this->form->input_text('Telefonszám', 'phone');
    $this->form->select('Státusz', 'generation', $this->GENERATIONS);
    $this->form->textarea('Bemutatkozás', 'description');
    $this->form->textarea('Gyermekeim neve és születési éve', 'children');

    if ($this->sm->check_right('users-edit')) {
        $this->form->input_text('Születési dátum (év-hónap-nap)', 'birthdate');
    }

    if ( $this->sm->check_right('users-active') )
    {
	$this->form->select('Hibernálás', 'active', array('Igen', 'Nem'), 'noselect=true');
    }

    $interests = get_config('INTERESTS');

    $this->header('Érdeklődési kör');
    echo '<p>Kérjük, jelöld meg, hogy az alábbi kategóriák közül mi érdekel Téged.</p>';

    foreach ($interests as $interest_id => $interest) {
        $this->form->checkbox($interest, 'interests_categories[]', 'value=' . $interest_id . (in_array($interest_id, explode(',', $_REQUEST['interests_categories'])) ? '&checked=checked': ''));
        echo '<div class="clearer"></div>';
    }

    $this->form->input_text('Egyéb érdeklődési kör', 'interests');

    $this->header('Profilkép');
    $this->form->input_file('Kép (.jpg, .jpeg, .png)', 'picture');

    $this->form->text('<img src="' . $this->ITEM->get_image() . '" alt="' . $this->ITEM->name . '" id="profile_picture" />');

    $this->form->close('Mentés');

    if ( $this->sm->check_right('users-password')
    ||   $this->ITEM->id == $this->sm->uid )
    {
        $this->header('Jelszó módosítása');
        $this->form->open('profil_password');

	if ( !$this->sm->check_right('users-password')
	||   $this->sm->uid == $this->ITEM->id )
	{
	    $this->form->input_password('Jelenlegi jelszó', 'current_passwd');
	}

        $this->form->input_password('Új jelszó', 'new_passwd1');
        $this->form->input_password('Új jelszó újra', 'new_passwd2');
        $this->form->close('Mentés', 'button_name=passwd_update');
    }

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
