<?php

namespace App\Forms\Director;

use Kris\LaravelFormBuilder\Form;

class CreateDirectorForm extends Form
{
  protected $formOptions = [
    'method' => 'POST'
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

    if($this->model)
    {
      $rules = 'required|email|unique:people,email,'.$this->model->id;
    }
    else {
      $rules = 'required|email|unique:people,email';
    }


    $this->add('email','text', [
      'rules' => $rules,
      'label' => 'Email Address'
    ]);


    $this->add('submit', 'submit', ['label' => 'Save Director', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
