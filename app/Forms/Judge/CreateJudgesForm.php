<?php

namespace App\Forms\Judge;

use Kris\LaravelFormBuilder\Form;

class CreateJudgesForm extends Form
{
  protected $formOptions = [
    'method' => 'POST'
  ];

  public function buildForm()
  {
      $this->add('judges', 'collection', [
              'type' => 'form',
              'options' => [    // these are options for a single type
                  'class' => 'Judge\PrototypeForm',
                  'label' => false,
                  'wrapper' => [
                    'class' => 'judge_container well'
                  ]
              ]
          ]);

      $this->add('submit', 'submit', ['label' => 'Save Judges', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
