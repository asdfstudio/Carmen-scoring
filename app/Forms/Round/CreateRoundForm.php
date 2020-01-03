<?php

namespace App\Forms\Round;

use Kris\LaravelFormBuilder\Form;

class CreateRoundForm extends Form
{
  protected $formOptions = [
    'class' => 'create-round-form add-resource-form-prototype add-resource-form',
    'data-resource-type' => 'round'
  ];

  public function buildForm()
  {
      $this->add('name','text', [
        'rules' => 'required',
        'label' => 'Name of Round',
        'help_block'=> [
          'text' => 'Prelims, Semifinals, Finals, etc.'
        ]
      ]);

      $default_sequence_value = 1;

      if($this->data['division'] AND $this->data['division']->rounds)
      {
        $default_sequence_value = $this->data['division']->rounds->count() + 1;
      }

      $this->add('sequence','number', [
        'rules' => 'required',
        'label' => 'Sequence / Round Order',
        'default_value' => $default_sequence_value,
        'help_block'=> [
          'text' => 'When does this round take place in this division?'
        ]
      ]);


      if($this->model AND $this->model->sources->count() > 0)
      {
        $default_choir_source_value = 'some';
      }
      else {
        $default_choir_source_value = 'all';
      }

      $this->add('choir_source','choice', [
        'rules' => 'required',
        'label' => 'Which choirs can compete in this round?',
        'default_value' => $default_choir_source_value,
        'choices' => [
          'all' => 'All choirs in this division',
          'some' => 'Some choirs from other rounds'
        ],
        'choice_options' => [
          'wrapper' => ['class' => 'choice-container'],
          'labelAttrs' => 'label-attr'
        ],
        'expanded' => true,
        'multiple' => false
      ]);

      if($this->model)
      {

      }

      $default_max_choirs_value = $this->model ? $this->model->max_choirs : 0;

      $this->add('max_choirs','number', [
        'rules' => 'required',
        'label' => 'How many choirs can participate in this round?',
        'default_value' => $default_max_choirs_value,
        'wrapper' => [
          'class' => 'max-choirs-container form-group'
        ]
      ]);

      $this->add('rounds', 'form', [
        'class' => 'Round\ChooseRoundsForm',
        'label' => 'Which round(s) should the choirs come from?',
        'formOptions' => [
          'data' => $this->data
        ],
        'wrapper' => [
          'class' => 'rounds-container form-group'
        ],
        'help_block'=> [
          'text' => 'Where do the participating choirs come from? If there is only one round in this division or this is the first round of the division, leave these all unchecked to allow all the choirs to compete. The source rounds must use the same scoring sheet as this round.'
        ]
      ]);


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
