<?php

namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class FinalizeScoringForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST',
      'disabled' => false,
      'id' => 'finalize_scoring_form'
    ];

    public function buildForm()
    {
        $this->add('finalize','hidden',['value' => '1']);
        $this->add('button', 'button', [
          'label' => 'Send scores and feedback', 
          'attr' => [
            'class' => $this->formOptions['disabled'] ? 'action disabled' : 'action',
            'onclick' => 'confirmAndSubmit("You are about to send scores and feedback to all participating directors in this division. This action can be undone by clicking ‘activate scoring’. If you do activate scoring again, the directors will be blocked from viewing all scores and feedback until the ‘send scores and feedback’ button is clicked again.", "finalize_scoring_form")'
          ]
        ]);
    }
}
