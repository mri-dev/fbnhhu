<?php

class content_controller 
{
    private $scr;

    function __construct () 
    {
	$this->scr = new screen(__FILE__);

	$this->grouplist = new grouplist();
	$this->articlelist = new articlelist();
	$this->newsletterlist = new newsletterlist();
    }

    function display () 
    {
	/**
	 * ellenorizzuk hogy a tartalom fix_url vagy sima url-kent szerepel
	 * ha nem akkor a tartalmat nem fogjuk megtalalni elmeletileg
	 */
	($group = $this->grouplist->get($_REQUEST['_ACTION'], 'url')) || ($group = $this->grouplist->get('/' . $_REQUEST['_CONTROLLER'] . '/' . $_REQUEST['_ACTION'], 'fix_url'));

	if ( $group instanceof group )
	{
	    if ( !$group->public && !$this->scr->sm->is_logged() )
	    {
		display_controller('error', 'action=404&error=Az oldal megtekintéséhez bejelentkezés szükséges.');

		return false;
	    }
	}
	else
	{
	    display_controller('error', 'action=404&error=Az oldal nem található.');

	    return false;
	}

	$this->scr->register_var('GROUP', $group);
	$this->scr->register_var('DOCUMENTS', $group->get_documents());

	switch ( $_REQUEST['_ACTION'] )
	{
	    case 'newsletters' :
		if ( isset($_REQUEST['_ID']) )
		{
		    $id = explode('_', $_REQUEST['_ID']);
		    $item = $this->newsletterlist->get($id[0]);

		    if ( !($item instanceof newsletter) )
		    {
			display_controller('error', 'action=404&error=Az oldal nem található.');
		    
			break;
		    }

		    $this->scr->register_var('ITEM', $item);

		    /**
		     * lecsereljuk a valtozokat
		     */
                    $name = explode(' ', $this->scr->sm->user->name);

                    $vars = array(
                        'fullname'      => $member->name,
                        'firstname'     => $name[count($name) - 1],
                    );

                    $content = preg_replace(array('#%([a-zA-Z_0-9]+)%#e'), array('$vars[\'$1\']'), $item->get_newsletter_html(false));

		    $this->scr->register_var('REPLACED_CONTENT', $content);

		    $this->scr->set_screen('NEWSLETTER');
		}
		else
		{
		    $this->newsletterlist->filter = new filter('approved IS NOT');
		    $this->newsletterlist->set_order('approved', true);

		    $this->newsletterlist->set_limit(20);

		    $items = $this->newsletterlist->get_list();
		
		    $this->scr->table->set_pages($this->newsletterlist->get_pages());
		    $this->scr->register_var('ITEMS', $items);

		    $this->scr->set_screen('NEWSLETTERLIST');
		}

		/**
		 * sidebar
		 */
		$this->newsletterlist->filter = new filter('approved IS NOT');
		
		if ( $item )
		{
		    $this->newsletterlist->filter->where('id <>', $item->id);
		}

		$this->newsletterlist->set_order('approved', true);

		$this->newsletterlist->disable_pager(true);
		$this->newsletterlist->set_limit(0, 10);

		$this->scr->register_var('LAST_ITEMS', $this->newsletterlist->get_list(true));
		$this->scr->set_screen('NEWSLETTER_SIDEBAR');

		break;

	    default :
	        $this->scr->register_var('LAST_ARTICLES', $this->articlelist->get_last_articles($this->scr->sm->user, 5));

		$this->scr->set_screen('CONTENT');

		$parent = $group->get_parent();

		/**
		 * ha a szulo nem a root
		 * akkor a sidebarban a testver menuk szerepelnek
		 */
		if ( $parent && $parent->id != 1 )
		{
		    $this->scr->register_var('PARENT', $parent);
		    $this->scr->register_var('CHILDRENLIST', $this->grouplist->get_children($parent->id));
		}

		$this->scr->set_screen('CONTENT_SIDEBAR');

		break;
	}
    }
}