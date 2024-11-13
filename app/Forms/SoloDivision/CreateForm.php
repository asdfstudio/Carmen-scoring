<?php

namespace App\Forms\SoloDivision;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);

        $this->add('sheet_id','entity', [
					'class' => 'App\Sheet',
					'empty_value' => 'Choose scoring sheet...',
					'label' => 'Scoring Sheet',
          'label_attr' => ['class' => 'block'],
          'expanded' => false,
          'multiple' => false,
          //'wrapper' => ['class' => 'wrap'],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
            'labelAttrs' => 'label-attr'
          ]
				]);

        $this->add('max_performers','number', [
          'rules' => 'required',
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);

        $this->add('category_1','text', [
          'label' => 'Category #1 Name'
        ]);

        $this->add('category_2','text', [
          'label' => 'Category #2 Name'
        ]);

        $this->add('submit', 'submit', [
          'label' => 'Save Solo Class',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
    }
}
