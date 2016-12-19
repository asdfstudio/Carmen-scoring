<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class DeactivateScoringForm extends Form
{

    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('deactivate','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Deactivate Scoring', 'attr' => ['class' => 'action']]);
    }
}
