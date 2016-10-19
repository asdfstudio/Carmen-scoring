<?php

namespace App\Forms\Round;

use Kris\LaravelFormBuilder\Form;

class CreateRoundForm extends Form
{
  public function buildForm()
  {
      $this->add('name','text', [
        'rules' => 'required',
        'label' => 'Name'
      ]);

      /*$this->add('submit', 'submit', [
        'label' => 'Save Round',
        'attr' => ['class' => 'btn btn-primary']
      ]);*/

      $this->add('submit', 'submit', [
        'label' => 'Save Round',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);

      $this->add('submit_create_another', 'submit', [
        'label' => 'Save & Create Another',
        'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
      ]);
  }
}
