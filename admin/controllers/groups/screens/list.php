<?php
    echo '<section class="fullpage">';
    $this->header('Statikus tartalmak', null);
    echo '<div class="content-page">';

    $this->message('<a href="/admin/groups/add' . (isset($_REQUEST['_ID']) ? '/' . $_REQUEST['_ID'] : '') . '">' . icon('add', 'új kategória hozzáadása') . ' Új kategória hozzáadása</a>' . ($this->PARENT instanceof group ? ' <a href="/admin/groups/list/' . $this->PARENT->id . '" style="padding-left: 10px;">' . icon('back', 'ugrás a szülőkönyvtárba') . ' Ugrás a szülő menübe</a>' : ''));

    $this->form->dl	= false;
    $this->form->nodiv	= true;

    $this->form->open();

    $this->table->set_class('table sortable');
    $this->table->set_header('', 'Név', 'Fix URL', '', '');

    $this->table->extras = array(
	0 => array('width' => '25px'),
	3 => array('width' => '25px'),
	4 => array('width' => '25px'),
    );

    if ( count($this->ITEMS) )
    {
	foreach ( $this->ITEMS as $row )
	{
	    $this->table->add_row(
		icon('subfolder', 'listázás', '/admin/groups/list/' . $row->id),
		$row->name,
		$row->fix_url,
		icon('edit', 'szerkesztés', '/admin/groups/edit/' . $row->id),
		($row->rgt - $row->lft == 1 ? icon('delete', 'törlés', '/admin/groups/delete/' . $row->id, 'ajax-delete') : '&nbsp;')
	    );

	    $this->table->set_row_id('item' . $row->id);

	    $listorder[] = $row->id;
	}
    }

    $this->table->flush_table();

    $this->form->input_hidden('listorder', $listorder ? implode(',', $listorder) : null);
    $this->form->close('Sorrend mentése');

    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
