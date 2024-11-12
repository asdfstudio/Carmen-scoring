<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

class CreateChoirForm extends Form
{
  protected $formOptions = [
    'id' => 'create-choir-form',
    'class' => 'add-resource-form-prototype add-resource-form dg-add-choir-form',
    'data-resource-type' => 'choir'
  ];

  public function buildForm()
    {

        $this->add('choir_id','choice', [
          'choices' => $this->data,
          'empty_value' => 'Choose ensemble...',
          'label' => 'Choose from existing ensembles',
          'attr' => ['class' => 'choir_id form-control'],
          'rules' => ['required_without:name'],
          'wrapper' => ['class' => 'existing_choir_container text-left']
        ]);

        $this->add('add_new_choir','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-choir-container btn btn-secondary'],
          'value' => 'Or create a new ensemble',
          'label_show' => false,
          'wrapper' => ['class' => 'text-left']
        ]);

        $this->add('heading', 'static', [
          'tag' => 'h2',
          'value' => 'Ensemble',
          'label_show' => false,
          'attr' => ['class' => 'new_choir_container text-left']
        ]);

        // Create a Choir
        $this->add('name','text', [
          'rules' => '',
          'wrapper' => ['class' => 'new_choir_container form-group text-left'],
          'label' => 'Ensemble Name',
          'rules' => ['required_without:choir_id']
        ]);

        // Add a school when creating a choir
        $this->add('school_heading', 'static', [
          'tag' => 'h2',
          'value' => 'School',
          'label_show' => false,
          'attr' => ['class' => 'new_choir_container text-left']
        ]);

        $this->add('school_id','entity', [
          'class' => 'App\School',
          'empty_value' => 'Choose school...',
          'attr' => ['class' => 'form-control'],
          'label' => 'Add Existing School',
          'rules' => ['required_without_all:choir_id,school.name'],
          'wrapper' => ['class' => 'new_choir_container existing_school_container text-left']
        ]);

        $this->add('add_new_school','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-school-container btn btn-secondary'],
          'value' => 'Or create a new school',
          'label_show' => false,
          'wrapper' => ['class' => 'new_choir_container text-left']
        ]);

        $this->add('school', 'form', [
          'class' => $this->formBuilder->create('School\SchoolForm'),
          'wrapper' => ['class' => 'new_choir_container new_school_container'],
          'label_show' => false
        ]);

        $this->add('director', 'form', [
          'class' => $this->formBuilder->create('Director\DirectorForm'),
          'wrapper' => ['class' => 'new_choir_container'],
          'label_show' => false,
          'label' => 'Ensemble Director'
        ]);

        $this->add('receives_rankings', 'checkbox', [
            'value' => 1,
            'checked' => true,
            'label' => 'Can receive rankings'
        ]);

        $this->add('receives_ratings', 'checkbox', [
            'value' => 1,
            'checked' => true,
            'label' => 'Can receive ratings'
        ]);

        $this->add('choral_sweepstakes', 'checkbox', [
          'value' => 1,
          'checked' => true,
          'label' => 'Choral Sweepstakes Qualification',
      ]);
      
      $this->add('instrumental_sweepstakes', 'checkbox', [
          'value' => 1,
          'checked' => true,
          'label' => 'Instrumental Sweepstakes Qualification',
      ]);
      
      $this->add('festival_sweepstakes', 'checkbox', [
          'value' => 1,
          'checked' => true,
          'label' => 'Festival Sweepstakes Qualification',
      ]);
        

        // Submit
        $this->add('submit', 'submit', [
          'label' => 'Save Ensemble',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

        /*
        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Add Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
        */
    }
}
