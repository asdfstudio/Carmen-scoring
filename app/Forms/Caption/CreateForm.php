<?php

namespace App\Forms\Caption;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
  protected $formOptions = [

  ];

  public function buildForm()
  {
      $this->add('name','text', [
        'rules' => 'required',
        'label' => 'Name'
      ]);

      $this->add('color_id','choice', [
        'rules' => 'required',
        'label' => 'Color',
        'multiple' => false,
        'expanded' => false,
        'choice_options' => [
          'wrapper' => ['class' => 'choice-container'],
        ],
        'choices' => [
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
          6 => 6,
          7 => 7,
          8 => 8,
          9 => 9,
          10 => 10,
          11 => 11,
          12 => 12
        ]
      ]);

      /*$hex_color = false;

      if ($this->model) {
        $hex_color = $this->model->hex_color;
      }

      $this->add('hex_color','text', [
        'rules' => 'required',
        'label' => 'Color (Hex code)',
        'attr' => [
          'style' => 'border-left: 4px solid ' . $hex_color
        ]
      ]);*/

      $this->add('submit', 'submit', [
        'label' => 'Save Caption',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);
  }
}
