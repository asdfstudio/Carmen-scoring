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
      'label' => 'First Name',
      'default_value' => $this->model->person->first_name
    ]);

    $this->add('last_name','text', [
      'rules' => 'required',
      'label' => 'Last Name',
      'default_value' => $this->model->person->last_name
    ]);

    $this->add('email','text', [
      'rules' => 'required|email|unique:users,email,'.$this->model->id,
      'label' => 'Email Address'
    ]);

    $this->add('username','text', [
      'rules' => 'required|unique:users,username,'.$this->model->id,
      'label' => 'Username'
    ]);

    $this->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
