<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CloseCompetitionForm extends Form
{
    protected $formOptions = [
      'method' => 'POST',
      'id' => 'close_competition_form'
    ];

    public function buildForm()
    {
        $this->add('complete','hidden',['value' => '1']);
        $this->add('button', 'button', [
          'label' => 'Close Competition',
          'attr' => [
            'class' => 'action',
            'onclick' => 'confirmAndSubmit("You are about to close the entire competition. This prevents any further additions, changes, or results to be made. This action can only be undone by contacting a Carmen Administrator. You should only take this action after the entire competition is complete and all scores have been sent.", "close_competition_form")'
          ]
        ]);
    }
}
