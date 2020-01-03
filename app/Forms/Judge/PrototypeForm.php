<?php

namespace App\Forms\Judge;

use Kris\LaravelFormBuilder\Form;

class PrototypeForm extends Form
{
    public function buildForm()
    {
      $this->add('judge_id','entity', [
        'class' => 'App\Judge',
        'property' => 'first_name',
        'empty_value' => 'Choose judge...',
        'label' => 'Choose an Existing Judge',
        'attr' => ['class' => 'judge_id'],
        'wrapper' => ['class' => 'existing_judge_container']
      ]);

      $this->add('add_new_judge','static', [
        'tag' => 'a',
        'attr' => ['class' => 'toggle-new-judge-container btn btn-secondary'],
        'value' => 'Create a new judge',
        'label_show' => false
      ]);

      // Add a new judge
      $this->add('judge', 'form', [
        'class' => $this->formBuilder->create('Judge\CreateJudgeForm'),
        'wrapper' => ['class' => 'new_judge_container'],
        'label_show' => false
      ]);

      $this->add('caption_id','entity', [
        'class' => 'App\Caption',
        'empty_value' => 'Choose caption ...',
        'label' => 'Caption',
        'expanded' => true,
        'multiple' => true,
        'choice_options' => [
          'wrapper' => ['class' => 'choice-wrapper'],
          'labelAttrs' => 'label-attr'
      ],
      ]);
    }
}
