<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class CompleteScoringForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST',
      'id' => 'complete_scoring_form'
    ];

    public function buildForm()
    {
        if ($this->getData('isMissingScores')) {
          $btnAttr = ['class' => 'action disabled', 'disabled' => 'disabled'];
        } else {
          $btnAttr = ['class' => 'action'];
        }

        $btnAttr['onclick'] = 'confirmAndSubmit("You are about to lock in scores for this division. This action prevents the judges from making any changes and it calculates the results. '.
            'This action can be undone by clicking ‘activate scoring’.", null, this);';

        $this->add('complete','hidden',['value' => '1']);
        $this->add('button', 'button', [
          'label' => 'Complete Scoring',
          'attr' => $btnAttr
        ]);
    }
}
