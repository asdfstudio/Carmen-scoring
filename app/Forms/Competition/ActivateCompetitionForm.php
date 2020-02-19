<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class ActivateCompetitionForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('activate','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Activate Competition', 'attr' => ['class' => 'action']]);
    }
}
