<?php

namespace App\Forms\Caption;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
  protected $formOptions = [

  ];

  public function buildForm()
  {
      $this->add('name','text', [
        'rules' => 'required',
        'label' => 'Name'
      ]);

      $this->add('submit', 'submit', [
        'label' => 'Save Caption',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);
  }
}
