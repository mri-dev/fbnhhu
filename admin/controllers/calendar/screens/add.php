<?php
    echo '<section class="fullpage">';
    $this->header('Új esemény hozzáadása');
    echo '<div class="content-page">';

    $this->form->open('add-calendar-event');
    $this->form->input_text('Megnevezés', 'title');
    $this->form->input_date('Dátum', 'date');
    $this->form->input_text('Időpont (HH:mm)', 'time');
    $this->form->input_text('Helyszín', 'location');
    $this->form->select('Rendezvény jellege', 'type', $this->TYPES);
    $this->form->editor('Leírás', 'content');
    $this->form->close('Mentés');
    
    echo '<div class="clearer"></div>';
    echo '</div>';
    echo '</section>';
