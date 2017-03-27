<?php
    echo '<div class="clearer"></div>',
	 '</div>', # container
	 '<footer id="footer">',
	 '<div id="partners" class="page-width-holder">',
	 '<h3>Partnereink</h3>';

    if ( $this->PARTNERS )
    {
      echo '<div class="logo-holder">';
    	foreach ( $this->PARTNERS as $item )
    	{
    	    list($width, $height) = getimagesize($this->PARTNERS_IMAGEDIR . $item['image']);

    	    echo '<a href="' . $item['url'] . '" style="width: ' . $width . 'px; background-image: url(' . $this->PARTNERS_IMAGEURL . $item['image'] . ');"' . ($item['tooltip'] ? ' class="tooltip" title="' . $item['tooltip'] . '"' : '') . '></a>';
    	}
      echo '</div>';
    }

    echo '<h3>Tagjaink</h3>';

    if ( $this->MEMBERS )
    {
        echo '<div class="logo-holder">';
      	foreach ( $this->MEMBERS as $item )
      	{
      	    list($width, $height) = getimagesize($this->MEMBERS_IMAGEDIR . $item['image']);

      	    echo '<a href="' . $item['url'] . '" style="width: ' . $width . 'px; background-image: url(' . $this->MEMBERS_IMAGEURL . $item['image'] . ');"' . ($item['tooltip'] ? ' class="tooltip" title="' . $item['tooltip'] . '"' : '') . '></a>';
      	}
      echo '</div>';
    }

    echo '<div class="clearer"></div>',
	 '</div>',

	 '<div id="sitemap">',
   '<div class="page-width-holder">',

	 '<section id="child1">',
	 '<article>',
	 '<h3>Az FBN-H-ról</h3>';

    foreach ( $this->GROUPS_ABOUT as $item )
    {
	echo '<p><a href="' . $item->get_url() . '">' . $item->name . '</a></p>';
    }

    echo '</article>',
	 '</section>',

	 '<section id="child2">',
	 '<article>',
	 '<h3>Fórumok</h3>';

    foreach ( $this->GROUPS_FORUMS as $item )
    {
	echo '<p><a href="' . $item->get_url() . '">' . $item->name . '</a></p>';
    }

    echo '</article>',
	 '</section>',

	 '<section id="child3">',
	 '<article>',
	 '<h3>Kapcsolat</h3>',
	 '<p>Felelős Családi Vállalatokért Magyarországon Közhasznú Egyesület</p>',
	 '<p>Cím: 1117 Budapest, Budafoki út 183.</p>',
	 '<p>Adószám: 18034972-1-41</p>',
	 '<p>Számlaszám: 12001008-01230060-00100003</p>',
	 '<p>E-mail: <a href="mailto:info@fbn-h.hu">info@fbn-h.hu</a></p>',
	 '</article>',
	 '</section>',
   '</div>',


	 '</div>', # sitemap
   '<section class="copyright"><div class="page-width-holder">&copy 2012 ' . (date('Y') > 2012 ? ' - ' . date('Y') : '') . ' Felelős Családi Vállalkozások Magyarországon Egyesület</div></section>',
	 '</footer>',

	 '<div id="dialog" class="reveal-modal"></div>',
	 '</body>',
	 '</html>';
