<?php

    echo '<section class="list-left">';
    $this->header('Cégprofilok keresése <a class="creator" href="/admin/companies/add">új cégprofil felvétele</a>', null);
    echo '<div class="content-page">';


    if ( isset($_REQUEST['username']) )
    {
	echo '<div class="search-request">Keresett név: <span>"' . htmlspecialchars($_REQUEST['username']) . '" &middot; ' . count($this->ITEMCOUNT) . '</span> találat alább</div>';
    }

    if ( count($this->ITEMS) )
    {
	foreach ( $this->ITEMS as $item )
	{
	    $members = $item->get_members();
	    $documents = $item->get_documents();

	    echo '<div class="userblock">',
		 '<h2><a href="/admin/companies?id=' . $item->id . '">' . $item->name . '</a>' . ($item->owner->id == $this->sm->uid || $this->sm->check_right('companies-full') ? ' <a class="editing" href="/admin/companies/edit/' . $item->id . '">szerkesztés</a>' : '') . '</h2>',
		 '<div class="image">';

	    echo '<img src="' . $item->get_image() . '" alt="' . $item->name . '" />';

	    echo '</div>',
		 '<div class="data">';

	    echo '<table>',
		 '<tr><td class="r1"><label>URL:</label></td><td><span><a href="' . $item->url . '" target="_blank">' . $item->url . '</a></span></td></tr>',
		 ($item->employees ? '<tr><td class="r1"><label>Foglalkoztatottság:</label></td><td><span>' . $item->employees . '</span></td></tr>' : '');

	    if ($item->revenue) {
		echo '<tr><td class="r1"><label>Nettó éves árbevétel:</label></td><td><span>' . number_format(($item->revenue / 1000000), 0, '.', ' ') . ' millió Ft</span></td></tr>';
	    }
/*
	    $revenues = $item->get_revenues(false);

	    if ( $revenues['SINGLE'] )
	    {
		foreach ( $revenues['SINGLE'] as $year => $value )
		{
		    echo '<tr><td class="r1"><label>Nettó éves árbevétel (' . $year . '):</label></td><td><span>' . number_format(($value / 1000000), 0, '.', ' ') . ' millió Ft</span></td></tr>';
		}
	    }

	    if ( $revenues['GROUP'] )
	    {
		foreach ( $revenues['GROUP'] as $year => $value )
		{
		    echo '<tr><td class="r1"><label>Csoportszintű éves árbevétel (' . $year . '):</label></td><td><span>' . number_format(($value / 1000000), 0, '.', ' ') . ' millió Ft</span></td></tr>';
		}
	    }
*/
	    if ( $item->fb_contact )
	    {
		    echo '<tr><td class="r1"><label>Family Business kapcsolattartó családtag:</label></td><td><span><a href="/admin/users?username=' . $item->fb_contact->username . '">' . $item->fb_contact->name . '</a></span></td></tr>';
	    }

	    if ( $item->contact )
	    {
		    echo '<tr><td class="r1"><label>Céges kapcsolattartó:</label></td><td><span>' . $item->contact . '</span></td></tr>';

		    if ($item->contact_phone) {
			echo '<tr><td class="r1"><label>Kapcsolattartó telefonszáma:</label></td><td><span>' . $item->contact_phone . '</span></td></tr>';
		    }

		    if ($item->contact_email) {
			echo '<tr><td class="r1"><label>Kapcsolattartó e-mail címe:</label></td><td><span>' . $item->contact_email . '</span></td></tr>';
		    }
	    }

	    if ( $this->sm->check_right('companies-full') )
	    {
		    echo '<tr><td class="r1"><label>Cégprofilt karbantartja:</label></td><td><span>' . $item->owner->name . '</span></td></tr>';
	    }

	    if ( $members )
	    {
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><td class="r1"><label>Családtagok listája:</label></td><td><ul>';

		foreach ( $members as $member )
		{
		    echo '<li><a href="/admin/users?username=' . $member->user->username . '">' . $member->user->name . '</a></li>';
		}

		echo '</ul></td></tr>';
	    }

	    if ( $documents )
	    {
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><td class="r1"><label>Dokumentumok:</label></td><td><ul>';

		foreach ( $documents as $url )
		{
		    echo '<li><a href="' . $url . '">' . basename($url) . '</a></li>';
		}

		echo '</ul></td></tr>';
	    }

	    echo '</table>',
		 '</div>';

            if ( $this->ITEMCOUNT == 1 )
            {
                echo '<div class="description">',
                     '<div class="description-content">';

                if ( $item->description )
                {
                    echo '<h3>Bemutatkozás</h3>',
                         '<div>' . $item->description . '</div>';
                }

		if ($item->youtube_url) {
		    preg_match('#https?://(www\.)?youtube\.com/watch\?v=([a-zA-Z0-9]+)#', $item->youtube_url, $out);

		    if ($out) {
			echo '<h3>Bemutatkozó videó</h3>';

			echo '<iframe id="player" type="text/html" width="100%" height="250" src="http://www.youtube.com/embed/' . $out[2] . '" frameborder="0"></iframe>';
		    }
		}

                echo '</div>',
                     '</div>';
            }

	    echo '<div class="clearer"></div>',
		 '</div>';
	}

	echo $this->table->generate_pager();
    }
    else
    {

	$this->message('Nincs találat...', 'error');
    }

    echo '</div></section>',
	 '<section class="list-right">';
   echo '<section>';

    $this->form->dl = false;
    $this->form->method = 'get';

    $this->form->open('users-search');
    $this->form->input_text('Cégprofil keresése', 'username');
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
    echo '</section>';

    echo '</section>';
