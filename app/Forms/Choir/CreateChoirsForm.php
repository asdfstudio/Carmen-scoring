<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

class CreateChoirsForm extends Form
{
  protected $formOptions = [
    'method' => 'POST'
  ];

  public function buildForm()
  {
      $this->add('choirs', 'collection', [
              'type' => 'form',
              'options' => [    // these are options for a single type
                  'class' => 'Choir\PrototypeForm',
                  'label' => false,
                  'wrapper' => [
                    'class' => 'choir_container well'
                  ]
              ]
          ]);

      $this->add('submit', 'submit', ['label' => 'Save Choirs', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
