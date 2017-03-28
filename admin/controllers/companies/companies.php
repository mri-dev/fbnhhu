<?php

class companies_controller 
{
    private $scr;

    function __construct () 
    {
	$this->scr = new screen(__FILE__);

	$this->userlist		= new userlist();
	$this->complist		= new companylist();
	$this->memberlist 	= new company_memberlist();
    }

    function display () 
    {
	switch ( $_REQUEST['_ACTION'] ) 
	{
	    case 'delete-member' :
		$member = $this->memberlist->get($_REQUEST['_ID']);

		$ret['ok'] = false;

		if ( !($member instanceof company_member) 
		||   ($member->company->owner->id !== $this->scr->sm->uid
		&&   !$this->scr->sm->check_right('companies-full')) )
		{
		    $ret = array('mess' => 'Nem található ilyen cégtag.');
		}
		else
		{
		    if ( $member->delete() )
		    {
			$ret['ok'] = true;
		    }
		}

		echo json_encode($ret);

		break;
	
	    case 'edit' :
		$comp = $this->complist->get($_REQUEST['_ID']);

		if ( !($comp instanceof company) || ($comp->owner->id !== $this->scr->sm->uid && !$this->scr->sm->check_right('companies-full')) )
		{
		    display_controller('error', 'action=404&error=Hiba, nem található ilyen cégprofil.');
		    
		    break;
		}

                /**
                 * profilkep modositasa
                 */
                if ( isset($_FILES['picture'])
                &&   $_REQUEST['xhr'] == 1 )
                {
                    $return['ok'] = false;

                    if ( $_FILES['picture']['error'] == 0 )
                    {
                        $picture_dir = get_config('COMPANIES::PICTURES_DIR');
                        $picture_uri = get_config('COMPANIES::PICTURES_URI');

                        $picture_name = $comp->id . '_' . urlfriendly($comp->name) . '.jpg';

                        $image = new image($_FILES['picture']['tmp_name']);

                        if ( $image )
                        {
                            if ( $image->create_thumb(256, 256)
                            &&   $image->save_thumb_to($picture_dir . $picture_name) )
                            {
                                $return['ok'] = true;
                                $return['image'] = $picture_uri . $picture_name;
                            }
                        }
                    }

                    echo json_encode($return);

                    break;
                }

	        $this->scr->register_var('EXTENSIONS', $comp->extensions);

		/**
		 * upload documents
		 */
		if ( isset($_REQUEST['upload_file']) )
		{
		    $fileinfo = pathinfo($_FILES['compfile']['name']);
		    
		    if ( in_array(strtolower($fileinfo['extension']), $comp->extensions) )
		    {
			if ( is_dir($comp->document_dir . $comp->id) 
			||   mkdir($comp->document_dir . $comp->id) )
			{
			    move_uploaded_file($_FILES['compfile']['tmp_name'], $comp->document_dir . $comp->id . '/' . urlfriendly($fileinfo['filename']) . '.' . $fileinfo['extension']);
			}
		    }
		    else
		    {
			form_error('compfile', 'Nem engedélyezett kiterjesztés: ' . $fileinfo['extension']);
		    }
		}

		/**
		 * add new member
		 */
		if ( isset($_REQUEST['user_id']) )
		{
		    $member = new company_member(null, $comp, $this->userlist->get($_REQUEST['user_id']));

		    $ret['ok'] = true;
		    
		    if ( !$member->insert() )
		    {
			$ret = array('ok' => false, 'mess' => $member->errors ? implode('<br>', $member->errors): 'Ismeretlen hiba');
		    }
		    else
		    {
			$ret['id'] = $member->id;
			$ret['name'] = $member->user->name;
		    }
		    
		    echo json_encode($ret);
		    
		    break;
		}

		/**
		 * save company changes
		 */
		if ( isset($_REQUEST['submit-button']) )
		{
		    $temp_comp = new company($comp->id, $_REQUEST['name'], $this->fix_url($_REQUEST['comp_url']), $_REQUEST['employees'], $_REQUEST['description'], $this->userlist->get($_REQUEST['fb_contact']), $_REQUEST['contact'], ($this->scr->sm->check_right('companies-full') ? $this->userlist->get($_REQUEST['owner_id']) : $comp->owner), $_REQUEST['revenue'], $_REQUEST['contact_phone'], $_REQUEST['contact_email'], $_REQUEST['youtube_url']);
		    
		    if ( $temp_comp->update() )
		    {
/*
			if ( count($_REQUEST['single_revenues']) )
			{
			    foreach ( $_REQUEST['single_revenues'] as $year => $value )
			    {
				$temp_comp->revenues_update($year, intval(str_replace(' ', '', $value)));
			    }
			}

			if ( count($_REQUEST['group_revenues']) )
			{
			    foreach ( $_REQUEST['group_revenues'] as $year => $value )
			    {
				$temp_comp->revenues_update($year, intval(str_replace(' ', '', $value)), 'GROUP');
			    }
			}
*/
			redirect::location('/admin/companies?id=' . $comp->id);
		    }
		    else
		    {
			form_error($temp_comp->errors);
		    }
		}
		else
		{
		    $_REQUEST['name']			= $comp->name;
		    $_REQUEST['comp_url']		= $comp->url;
		    $_REQUEST['employees']		= $comp->employees;
		    $_REQUEST['description']		= $comp->description;
		    $_REQUEST['fb_contact']		= $comp->fb_contact->id;
		    $_REQUEST['contact']		= $comp->contact;
		    $_REQUEST['contact_phone']		= $comp->contact_phone;
		    $_REQUEST['contact_email']		= $comp->contact_email;
		    $_REQUEST['revenue']		= $comp->revenue;
		    $_REQUEST['youtube_url']		= $comp->youtube_url;
		    $_REQUEST['owner_id']		= $comp->owner->id;
/*
		    $comp->get_revenues();
		    
		    if ( $comp->revenues['SINGLE'] )
		    {
			foreach ( $comp->revenues['SINGLE'] as $year => $value )
			{
			    $_REQUEST['single_revenues'][$year] = number_format($value, 0, '.', ' ');
			}
		    }
		    
		    if ( $comp->revenues['GROUP'] )
		    {
			foreach ( $comp->revenues['GROUP'] as $year => $value )
			{
			    $_REQUEST['group_revenues'][$year] = number_format($value, 0, '.', ' ');
			}
		    }
*/
		}

		$this->userlist->set_limit(0);
		$this->userlist->set_order('name');
		
		$this->scr->register_var('ITEM', $comp);
		$this->scr->register_var('USERS', $this->userlist->get_list());	    
		$this->scr->register_var('MEMBERS', $comp->get_members());
		$this->scr->register_var('DOCUMENTS', $comp->get_documents());
		$this->scr->set_screen('EDIT');
		break;
	
	    case 'add' :
		if ( isset($_REQUEST['submit-button']) )
		{
		    $comp = new company(null, $_REQUEST['name'], $this->fix_url($_REQUEST['comp_url']), $_REQUEST['employees'], $_REQUEST['description'], $this->userlist->get($_REQUEST['fb_contact']), $_REQUEST['contact'], $this->scr->sm->user, $_REQUEST['revenue'], $_REQUEST['contact_phone'], $_REQUEST['contact_email'], $_REQUEST['youtube_url']);
		    
		    if ( $comp->insert() )
		    {
/*
			if ( count($_REQUEST['single_revenues']) )
			{
			    foreach ( $_REQUEST['single_revenues'] as $year => $value )
			    {
				$comp->revenues_update($year, $this->fix_revenues_value($value));
			    }
			}

			if ( count($_REQUEST['group_revenues']) )
			{
			    foreach ( $_REQUEST['group_revenues'] as $year => $value )
			    {
				$comp->revenues_update($year, $this->fix_revenues_value($value), 'GROUP');
			    }
			}
*/
			redirect::location('/admin/companies/edit/' . $comp->id);
		    }
		    else
		    {
			form_error($comp->errors);
		    }
		}
	    
		$this->scr->set_screen('ADD');
		break;
	
	    default : 
                $this->articlelist = new articlelist();
                $articles = $this->articlelist->get_last_articles($this->scr->sm->user);

                $this->scr->register_var('LAST_ARTICLES', $articles);

                if ( isset($_REQUEST['username']) )
                {
                    $this->complist->filter = new filter('name LIKE', '%' . $_REQUEST['username'] . '%');
                }

                if ( isset($_REQUEST['id']) )
                {
                    $this->complist->filter = new filter('id', $_REQUEST['id']);
                }

		$this->complist->set_order('name', false);

		$items = $this->complist->get_list();
		
		$this->scr->table->set_pages($this->complist->get_pages());

		$this->scr->register_var('ITEMCOUNT', $this->complist->num_rows);		
		$this->scr->register_var('ITEMS', $items);
		$this->scr->set_screen('LIST');
		break;
	}
    }

    function fix_url ($url)
    {
	if ( strlen($url)
	&&  !preg_match('#^https?://#', $url) )
	{
	    $url = 'http://' . $url;
	}

	return $url;
    }

    function fix_revenues_value ($value)
    {
	return intval(preg_replace('#[^\d]#', '', $value));
    }
}
