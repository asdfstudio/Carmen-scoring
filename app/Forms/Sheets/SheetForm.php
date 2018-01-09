<?php

namespace App\Forms\Sheets;

use Kris\LaravelFormBuilder\Form;

class SheetForm extends Form
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
        'label' => 'Save Sheet',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);
  }
}
