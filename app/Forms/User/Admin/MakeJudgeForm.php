<?php

namespace App\Forms\User\Admin;

use Kris\LaravelFormBuilder\Form;

class MakeJudgeForm extends Form
{
  protected $formOptions = [
    'method' => 'POST',
    'class' => 'form-inline'
  ];

  public function buildForm()
  {
      $this->add('submit', 'submit', [
        'label' => 'Assign as Judge',
        'attr' => ['class' => 'btn btn-secondary'],
        //'template' => ''
      ]);
  }
}
