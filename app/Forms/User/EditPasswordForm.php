<?php

namespace App\Forms\User;

use Kris\LaravelFormBuilder\Form;

class EditPasswordForm extends Form
{
  protected $formOptions = [
    'method' => 'PUT'
  ];

  public function buildForm()
  {
    //$this->add('email','static');

    $this->add('password','repeated', [
      'type' => 'password',
      'second_name' => 'password_confirmation',
      'first_options' => [
        'label' => 'New Password',
        'value' => '',
        'rules' => 'required|confirmed|min:4'
      ],
      'second_options' => [
        'label' => 'New Password Again',
        'rules' => 'required'
      ]
    ]);

    $this->add('previous_url', 'hidden', [
      'default_value' => $this->data['previous_url']
    ]);

    $this->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
