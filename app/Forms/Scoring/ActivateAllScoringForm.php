<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class ActivateAllScoringForm extends Form
{
    protected $formOptions = [
        'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('activate_all', 'hidden', ['value' => '1']);
        $this->add('submit', 'submit', [
            'label' => 'Activate All Scoring',
            'attr' => ['class' => 'action']
        ]);
    }
}
