<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CloseCompetitionForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('complete','hidden',['value' => '1']);
        $this->add('submit', 'submit', [
          'label' => 'Close Competition',
          'attr' => ['class' => 'action']
        ]);
    }
}
