<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class FinalizeScoringForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('finalize','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Finalize / Publish Scoring', 'attr' => ['class' => 'action']]);
    }
}
