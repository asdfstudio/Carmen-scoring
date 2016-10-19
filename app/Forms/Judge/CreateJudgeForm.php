<?php

namespace App\Forms\Judge;

use Kris\LaravelFormBuilder\Form;

class CreateJudgeForm extends Form
{
    public function buildForm()
    {
      $this->add('first_name','text', [
        'rules' => '',
        'label' => 'First Name',
        'rules' => ['required_without:judge_id'],
      ]);

      $this->add('last_name','text', [
        'rules' => '',
        'label' => 'Last Name',
        'rules' => ['required_without:judge_id'],
      ]);

      $this->add('email','text', [
        'rules' => '',
        'label' => 'Email Address',
        'rules' => ['required_without:judge_id', 'email','unique:users,email'],
      ]);
    }
}
