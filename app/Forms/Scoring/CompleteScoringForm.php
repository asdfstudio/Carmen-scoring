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
        $this->add('complete','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Complete Scoring', 'attr' => ['class' => 'action']]);
    }
}
