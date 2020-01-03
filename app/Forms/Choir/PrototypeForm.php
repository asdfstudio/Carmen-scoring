<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

class PrototypeForm extends Form
{
  protected $formOptions = [
        //'attr' => ['class' => 'choir_container']
  ];

  public function buildForm()
    {


        $this->add('choir_id','entity', [
          'class' => 'App\Choir',
          'empty_value' => 'Choose choir...',
          'label' => 'Choose an Existing Choir',
          'attr' => ['class' => 'choir_id'],
          'wrapper' => ['class' => 'existing_choir_container']
        ]);

        $this->add('add_new_choir','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-choir-container btn btn-secondary'],
          'value' => 'Create a new choir',
          'label_show' => false
        ]);

        // Create a Choir
        $this->add('name','text', [
          'rules' => '',
          'wrapper' => ['class' => 'new_choir_container'],
          'label' => 'Choir Name'
        ]);

        $this->add('school_id','entity', [
          'class' => 'App\School',
          'empty_value' => 'Choose school...',
          'label' => 'School',
          //'attr' => ['class' => 'selectize'],
          'wrapper' => ['class' => 'new_choir_container existing_school_container']
        ]);

        $this->add('add_new_school','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-school-container btn btn-secondary'],
          'value' => 'Create a new school',
          'label_show' => false,
          'wrapper' => ['class' => 'new_choir_container']
        ]);

        // Add a school when creating a choir
        $this->add('school', 'form', [
          'class' => $this->formBuilder->create('School\SchoolForm'),
          'wrapper' => ['class' => 'new_choir_container new_school_container'],
          'label_show' => false
        ]);
    }
}
