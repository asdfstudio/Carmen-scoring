<?php

namespace App\Forms\School;

use Kris\LaravelFormBuilder\Form;

class SchoolForm extends Form
{
    public function buildForm()
    {
        /*
        $this->add('heading', 'static', [
          'tag' => 'h2',
          'value' => 'School',
          'label_show' => false
        ]);
        */
        
        $this->add('name','text', [
          'rules' => '',
          'label' => 'School Name',
          'rules' => ['required_without_all:choir_id,school_id'],
        ]);

        $this->add('place', 'form', [
          'class' => $this->formBuilder->create('Place\ShortPlaceForm'),
          'label_show' => false
        ]);
    }
}
