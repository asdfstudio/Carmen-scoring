<?php

namespace App\Forms\Person;

use Kris\LaravelFormBuilder\Form;

class EditPersonForm extends Form
{
  protected $formOptions = [
    'method' => 'PUT'
  ];

  public function buildForm()
  {
    $this->add('first_name','text', [
      'rules' => 'required',
      'label' => 'First Name'
    ]);

    $this->add('last_name','text', [
      'rules' => 'required',
      'label' => 'Last Name'
    ]);

    $this->add('email','text', [
      'rules' => 'required|email',
      'label' => 'Email Address'
    ]);

    $this->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
