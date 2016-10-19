<?php

namespace App\Forms\Round;

use Kris\LaravelFormBuilder\Form;

class CreateRoundsForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('rounds', 'collection', [
                'type' => 'form',
                'options' => [    // these are options for a single type
                    'class' => 'Round\PrototypeForm',
                    'label' => false,
                ]
            ]);

        $this->add('submit', 'submit', ['label' => 'Save Rounds', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
