<?php

namespace App\Forms\Judge;

use Kris\LaravelFormBuilder\Form;

class CreateJudgeForm extends Form
{
    public function buildForm()
    {
      $this->add('first_name','text', [
        'label' => 'First Name',
        'rules' => ['required_without:judge_id'],
        'wrapper' => ['class' => 'form-group text-left']
      ]);

      $this->add('last_name','text', [
        'label' => 'Last Name',
        'rules' => ['required_without:judge_id'],
        'wrapper' => ['class' => 'form-group text-left']
      ]);

      $this->add('email','email', [
        'label' => 'Email Address',
        'rules' => ['required_without:judge_id', 'email','unique:users,email'],
        'wrapper' => ['class' => 'form-group text-left']
      ]);
    }
}
