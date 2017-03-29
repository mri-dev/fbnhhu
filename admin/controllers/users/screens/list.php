<?php
    echo '<section class="list-left">';
    $this->header('Családtagok keresése' . ($this->sm->check_right('users-add') ? ' <a class="creator" href="/admin/users/add">új tag felvétele</a>' : ''), null);
    echo '<div class="content-page">';

    if ( isset($_REQUEST['username']) )
    {
	echo '<div class="search-request">Keresett név: <span>"' . htmlspecialchars($_REQUEST['username']) . '" &middot; ' . $this->ITEMCOUNT . '</span> találat alább</div>';
    }

    if ( count($this->ITEMS) )
    {
	foreach ( $this->ITEMS as $user )
	{
	    $companylist	= $user->get_companies();
	    $familymembers	= $user->get_family_members();
	    $levlists		= $user->get_levlists();

	    if ( $this->ITEMCOUNT == 1 )
	    {
 		echo '<div class="userblock">',
		     '<h2><a href="/admin/users?username=' . $user->username . '">' . $user->name . '</a>' . ($this->sm->check_right('users-edit') ? ' <a class="editing" href="/admin/users/edit/' . $user->id . '">szerkesztés</a>' : '') . ($this->sm->check_right('users-delete') ? ' <a class="deleting" href="/admin/users/delete/' . $user->id . '" class="delete-user">törlés</a>' : '') . '</h2>',
		     '<div class="image">';

		echo '<img src="' . $user->get_image() . '" alt="' . $user->name . '" />';

	        echo '</div>',
		     '<div class="data">';

	        echo '<table>',
		     '<tr><td class="r1"><label>Név</label></td><td><span><a href="/admin/users?username=' . $user->username . '">' . $user->name . '</a></span></td></tr>',
		     '<tr><td class="r1"><label>Státusz:</label></td><td><span>' . $this->GENERATIONS[$user->generation] . '</span></td></tr>';

	        if ( $companylist )
	        {
		    echo '<tr><td class="r1"><label>Cég / Cégek:</label></td><td><ul>';

		    foreach ( $companylist as $item )
		    {
		        echo '<li><a href="/admin/companies?id=' . $item->id . '">' . $item->name . '</a></li>';
		    }

		    echo '</ul></td></tr>';
	        }

	        if ($levlists )
	        {
		    echo '<tr><td class="r1"><label>Fórumok:</label></td><td><ul>';

		    foreach ( $levlists as $key => $item )
		    {
		        $forum = $item->get_forum();

		        echo '<li>' . ($forum instanceof forum ? '<a href="' . $forum->get_url() . '">' . $item->name . '</a>' : $item->name) . '</li>';
		    }

		    echo '</ul></td></tr>';
	        }

	        echo ($user->phone ? '<tr><td class="r1"><label>Telefonszám:</label></td><td><span>' . $user->phone . '</span></td></tr>' : ''),
		     '<tr><td class="r1"><label>Email:</label></td><td><span><a href="mailto:' . $user->email . '">' . $user->email . '</a></span></td></tr>';

	        if ($familymembers )
	        {
		    echo '<tr><td colspan="2">&nbsp;</td></tr>',
		         '<tr><td class="r1"><label>Családtagok:</label></td><td><ul>';

		    foreach ( $familymembers as $key => $item )
		    {
		        echo '<li><a href="/admin/users?username=' . $item->username . '">' . $item->name . '</a></li>';
		    }

		    echo '</ul></td></tr>';
	        }

	        echo '</table>',
		     '</div>';

	        if ( ($user->description || $user->interests) )
	        {
		    echo '<div class="description">',
		         '<div class="description-content">';

		    if ( $user->description )
		    {
		        echo '<h3>Bemutatkozás</h3>',
			     '<div>' . $user->description . '</div>';
		    }

		    if ( $user->children )
		    {
		        echo '<h3>Gyermekek</h3>',
			     '<div>' . $user->children . '</div>';
		    }

		    if ( $user->interests || $user->interests_categories )
		    {
		        echo '<h3>Érdeklődési kör</h3>';
			echo '<div>';

			$interests = get_config('INTERESTS');
			$items = explode(',', $user->interests_categories);

			foreach ($items as $item) {
			    if (isset($interests[$item])) {
				echo $interests[$item] . '<br>';
			    }
			}

			if ($user->interests) {
		    	    echo $user->interests;
			}

			echo '</div>';
		    }

		    echo '</div>',
		         '</div>';
		}

		echo '<div class="clearer"></div>',
		     '</div>';
	    }
	    else
	    {
 		echo '<div class="userblock single">',
		     '<h2><a href="/admin/users?username=' . $user->username . '">' . $user->name . '</a>' . ($this->sm->check_right('users-edit') ? ' <a class="editing" href="/admin/users/edit/' . $user->id . '">szerkesztés</a>' : '') . ($this->sm->check_right('users-delete') ? ' <a class="deleting" href="/admin/users/delete/' . $user->id . '" class="delete-user">törlés</a>' : '') . '</h2>',
		     '<header>';

	        if ( $companylist )
	        {
		        echo '<span>Cég / Cégek:</span> ';

    		    foreach ( $companylist as $item )
    		    {
    		        echo '<a href="/admin/companies?id=' . $item->id . '">' . $item->name . '</a> ';
    		    }
	        }

      		echo '<span>Státusz:</span> ' . $this->GENERATIONS[$user->generation] . ' <span>Email:</span> <a href="mailto:' . $user->email . '">' . $user->email . '</a>';

      		echo '</div>',
		     '</header>';
	    }
	}

	echo $this->table->generate_pager();
    }
    else
    {

	       $this->message('Nincs találat...', 'error');
    }

    echo '</div></section>',
	 '<section class="list-right"><section>';

    $this->form->dl = false;
    $this->form->method = 'get';

    $this->form->open('users-search');
    $this->form->input_text('Családtag keresése', 'username');
    $this->form->close(false);

    if ( $this->LAST_ARTICLES )
    {
        echo '<section id="last-articles">';

	        $this->header('Friss hírek');

        foreach ( $this->LAST_ARTICLES as $article )
        {
            echo '<article class="latest-article-item">',
                 '<h3><a href="' . $article->get_url() . '">' . $article->title . '</a></h3>',
                 '<div>' . $article->get_lead() . '</div>',
                 '<header><span>Írta:</span> <a href="/admin/users?username=' . $article->owner->username . '">' . $article->owner->name . '</a> &bull; <span>Dátum:</span> ' . format_date($article->created_at) . '</header>',
                 '<div class="more"><a href="/cikkek/' . $article->id . '_' . urlfriendly($article->title) . '">Tovább a cikkhez</a></div>',
                 '<div class="clearer"></div></article>';
        }

        echo '</section>';
    }

    echo '<section></section>';
