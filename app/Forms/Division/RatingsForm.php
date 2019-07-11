<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class RatingsForm extends Form
{
    public function buildForm()
    {
      
      $this->add('name', 'text', [
        'label' => 'Rating Name',
        'wrapper' => [
          'class' => 'form-group col-md-6 col-xs-12'
        ]
      ]);

      $this->add('min_score', 'number', [
        'label' => 'Minimum % of Total Available Score',
        'attr' => [
          'min' => 0,
          'max' => 100
        ],
        'wrapper' => [
          'class' => 'form-group col-md-5 col-xs-10'
        ]
      ]);
      
      $this->add('remove_rating', 'button', [
        'wrapper' => ['class' => 'remove-rating form-group col-md-1 col-xs-2'],
        'label' => 'X'
      ]);
      
    }
}
