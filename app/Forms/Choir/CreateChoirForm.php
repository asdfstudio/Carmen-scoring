<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

class CreateChoirForm extends Form
{
  protected $formOptions = [
    'class' => 'add-resource-form-prototype add-resource-form',
    'data-resource-type' => 'choir'
  ];

  public function buildForm()
    {

        /*$this->add('choir_id','entity', [
          'class' => 'App\Choir',
          'empty_value' => 'Choose choir...',
          'label' => 'Choose from existing choirs',
          'attr' => ['class' => 'choir_id'],
          'rules' => ['required_without:name'],
          'wrapper' => ['class' => 'existing_choir_container']
        ]);*/

        $this->add('choir_id','choice', [
          'choices' => $this->data,
          'empty_value' => 'Choose choir...',
          'label' => 'Choose from existing choirs',
          'attr' => ['class' => 'choir_id', 'id' => ''],
          'rules' => ['required_without:name'],
          'wrapper' => ['class' => 'existing_choir_container']
        ]);

        $this->add('add_new_choir','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-choir-container btn btn-secondary'],
          'value' => 'Create a new choir',
          'label_show' => false
        ]);

        $this->add('heading', 'static', [
          'tag' => 'h2',
          'value' => 'Choir',
          'label_show' => false,
          'attr' => ['class' => 'new_choir_container']
        ]);

        // Create a Choir
        $this->add('name','text', [
          'rules' => '',
          'wrapper' => ['class' => 'new_choir_container'],
          'label' => 'Choir Name',
          'rules' => ['required_without:choir_id']
        ]);

        // Add a director when creating a choir
        $this->add('director', 'form', [
          'class' => $this->formBuilder->create('Director\DirectorForm'),
          'wrapper' => ['class' => 'new_choir_container'],
          'label_show' => false,
          'label' => 'Choir Director'
        ]);

        $this->add('school_id','entity', [
          'class' => 'App\School',
          'empty_value' => 'Choose school...',
          'label' => 'School',
          'rules' => ['required_without_all:choir_id,school.name'],
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


        $this->add('submit', 'submit', [
          'label' => 'Save Choir',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Add Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
    }
}
