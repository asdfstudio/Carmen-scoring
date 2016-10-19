<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class ActivateScoringForm extends Form
{
    protected $formOptions = [
      'class' => 'pull-left',
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('activate','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Activate Scoring', 'attr' => ['class' => 'action']]);
    }
}
