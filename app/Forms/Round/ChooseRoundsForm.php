<?php

namespace App\Forms\Round;

use Kris\LaravelFormBuilder\Form;

class ChooseRoundsForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'choice', [
          'choices' => $this->data['choices'],
          'selected' => $this->data['selected'],
          //'label' => 'Source Rounds',
          'label_show' => false,
          'expanded' => true,
          'multiple' => true,
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
            'labelAttrs' => 'label-class',
          ]
        ]);
    }
}
