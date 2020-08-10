<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class FinalizeScoringForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST',
      'disabled' => false,
    ];

    public function buildForm()
    {
        $this->add('finalize','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Send scores and feedback', 'attr' => ['class' => $this->formOptions['disabled'] ? 'action disabled' : 'action']]);
    }
}
