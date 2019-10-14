<?php

namespace App\Forms\Penalty;

use Kris\LaravelFormBuilder\Form;

class CreatePenaltyForm extends Form
{
    protected $formOptions = [
      'class' => 'add-resource-form-prototype add-resource-form',
      'data-resource-type' => 'penalty'
    ];

    public function buildForm()
    {
        /*$this->add('penalty', 'form', [
          'class' => $this->formBuilder->create('Penalty\PenaltyForm')
        ]);*/

        $this->add('name','text', [
          'rules' => 'required'
        ]);

        $this->add('description','textarea', [
          'rules' => ''
        ]);

        $this->add('amount','text', [
          'rules' => 'required'
        ]);

        $this->add('apply_per_judge', 'choice', [
          'choices' => [0 => 'No', 1 => 'Yes'],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-wrapper'],
            'labelAttrs' => 'label-attr',
        ],
          'expanded' => true,
          'multiple' => false
        ]);

        //$this->add('submit', 'submit', ['label' => 'Save Penalty', 'attr' => ['class' => 'btn btn-primary']]);

        $this->add('submit', 'submit', [
          'label' => 'Save Penalty',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Create Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
    }
}
