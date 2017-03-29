<?php
    echo '<section class="fullpage">';
    $this->header('Cikkek', null);
    echo '<div class="content-page">';

    $this->message('<a href="/admin/articles/add">' . icon('add', 'új cikk létrehozása') . ' Új cikk létrehozása</a>');

    if ( $this->ITEMS )
    {
	$this->table->set_header('Cím', 'Fórum', 'Publikus', 'Hozzászólások', 'Létrehozva', 'Utoljára módosítva', 'Szerző', '', '');

	$this->table->extras = array(
	    7 => array('width' => 25),
	    8 => array('width' => 25)
	);

	foreach ( $this->ITEMS as $row )
	{
	    $this->table->add_row(
		$row->title,
		$row->forum->name,
		$this->VISIBILITY[$row->public],
		$this->COMMENT_TYPES[$row->comments],
		$row->created_at,
		$row->updated_on,
		$row->owner->name,
		icon('edit', 'szerkesztés', '/admin/articles/edit/' . $row->id),
		icon('delete', 'törlés', '/admin/articles/delete/' . $row->id, 'ajax-delete')
	    );
	}

	$this->table->flush_table();
    }
    else
    {
	$this->message('Még nem töltött fel egy Cikket sem.', 'error');
    }
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
