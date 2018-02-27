<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class CompleteScoringForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST'
    ];

    public function buildForm()
    {
        if ($this->getData('isMissingScores')) {
          $btnAttr = ['class' => 'action disabled', 'disabled' => 'disabled'];
        } else {
          $btnAttr = ['class' => 'action'];
        }

        $this->add('complete','hidden',['value' => '1']);
        $this->add('submit', 'submit', [
          'label' => 'Complete Scoring',
          'attr' => $btnAttr
        ]);
    }
}
