<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class CreatePrototypeForm extends Form
{
    public function buildForm()
    {
      $this->add('name','text', ['rules' => 'required']);

      $this->add('caption_weighting_id','entity', [
        'class' => 'App\CaptionWeighting',
        'empty_value' => 'Choose caption weighting...',
        'label' => 'Caption Weighting',
        'expanded' => true,
        'multiple' => false
      ]);

      $this->add('scoring_method_id','entity', [
        'class' => 'App\ScoringMethod',
        'empty_value' => 'Choose scoring method...',
        'label' => 'Scoring Method'
      ]);

      $this->add('sheet_id','entity', [
        'class' => 'App\Sheet',
        'empty_value' => 'Choose scoring sheet...',
        'label' => 'Scoring Sheet'
      ]);
    }
}
