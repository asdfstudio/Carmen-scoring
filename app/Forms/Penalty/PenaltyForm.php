<?php

namespace App\Forms\Penalty;

use Kris\LaravelFormBuilder\Form;

class PenaltyForm extends Form
{
    public function buildForm()
    {
      $this->add('name','text', [
        'rules' => 'required'
      ]);

      $this->add('description','textarea', [
        'rules' => ''
      ]);
    }
}
