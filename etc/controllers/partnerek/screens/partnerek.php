<?php

echo '<div id="partners" class="page-width-holder">',
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
'</div>';
