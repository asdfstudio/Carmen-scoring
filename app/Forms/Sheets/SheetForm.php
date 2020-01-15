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

      $this->add('is_retired','choice', [
        'label_show' => false,
        'choices' => ['1' => '"Retired" from use'],
        'expanded' => true,
        'multiple' => true
      ]);

      /*$this->add('caption_sort_order','textarea', [
        'help_block' => [
          'text' => 'Enter 1 caption per line'
        ]
      ]);*/


      $this->add('submit', 'submit', [
        'label' => 'Save Sheet',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);
  }
}
