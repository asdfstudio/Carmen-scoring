<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class ReactivateScoringForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('reactivate','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Reactivate Scoring', 'attr' => ['class' => 'action']]);
    }
}
