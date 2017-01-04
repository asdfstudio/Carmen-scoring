<?php

namespace App\Forms\Director;

use Kris\LaravelFormBuilder\Form;

class DirectorForm extends Form
{
  protected $formOptions = [
    'method' => 'POST'
  ];

  public function buildForm()
  {
    $this->add('heading', 'static', [
      'tag' => 'h2',
      'value' => 'Director',
      'label_show' => false
    ]);

    $this->add('first_name','text', [
      'rules' => 'required_without_all:choir_id',
      'label' => 'First Name'
    ]);

    $this->add('last_name','text', [
      'rules' => 'required_without_all:choir_id',
      'label' => 'Last Name'
    ]);


    $this->add('email','text', [
      'rules' => 'required_without_all:choir_id|email|unique:people,email',
      'label' => 'Email Address'
    ]);

  }
}
