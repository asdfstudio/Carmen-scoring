<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class ChooseDivisionForm extends Form
{
    public function buildForm()
    {

      $this->add('id', 'choice', [
        'choices' => $this->data['choices'],
        //'selected' => $this->data['selected'],
        'label' => 'Choose division to import judges from',
        //'label_show' => false,
        'expanded' => true,
        'multiple' => false,
        'choice_options' => [
          'wrapper' => ['class' => 'choice-container'],
          'labelAttrs' => 'label-attr'
        ]
      ]);

        $this->add('submit', 'submit', ['label' => 'Import Judges', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
