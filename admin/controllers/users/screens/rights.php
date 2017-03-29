<?php
    echo '<section class="fullpage">';
    $this->header('Jogok', null);
    echo '<div class="content-page">';

    $this->form->open('user_rights');

    foreach ( $this->RIGHTS as $item )
    {
	     $this->form->checkbox($item['right_name'], 'right_' . $item['right']);
       echo '<div class="clearer"></div>';
    }

    echo '<div id="rights_forum_spec"' . (isset($_REQUEST['right_forums-full']) ? ' class="hidden"' : '') . '>';

    echo '<div class="clearer"></div>';
    $this->header('Fórum specifikus jogok', null, 'dotted-header-double spec-rights');

    foreach ( $this->FORUMS as $item )
    {
	echo '<h3>' . $item->name . '</h3>';

	echo '<div class="right_cont">',
	     '<div><input type="checkbox" value="' . $item->id . '" name="right_forum-add[]" id="forum-add_' . $item->id . '"' . (in_array($item->id, (array) $_REQUEST['right_forum-add']) ? ' checked="checked"' : '') . ' /> <label for="forum-add_' . $item->id . '">Publikálás</label></div>',
	     '<div><input type="checkbox" value="' . $item->id . '" name="right_forum-edit[]" id="forum-edit_' . $item->id . '"' . (in_array($item->id, (array) $_REQUEST['right_forum-edit']) ? ' checked="checked"' : '') . ' /> <label for="forum-edit_' . $item->id . '">Szerkesztés / Törlés</label></div>',
	     '<div><input type="checkbox" value="' . $item->id . '" name="right_forum-levlist-edit[]" id="forum-levlist-edit_' . $item->id . '"' . (in_array($item->id, (array) $_REQUEST['right_forum-levlist-edit']) ? ' checked="checked"' : '') . ' /> <label for="forum-levlist-edit_' . $item->id . '">Levelezőlista szerkesztése</label></div>',
	     '<div><input type="checkbox" value="' . $item->id . '" name="right_forum-levlist-send[]" id="forum-levlist-send_' . $item->id . '"' . (in_array($item->id, (array) $_REQUEST['right_forum-levlist-send']) ? ' checked="checked"' : '') . ' /> <label for="forum-levlist-send_' . $item->id . '">Hírlevélküldés</label></div>',
	     '<div class="clearer"></div>',
	     '</div>';
    }

    echo '</div>';

    $this->form->close('Mentés');

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
