<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CloneForm extends Form
{
    public function buildForm()
    {
        $this->add('competition_name', 'text', [
          //'default_value' => $division->name,
        ]);

        $this->add('clone_divisions', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all divisions?',
            //'wrapper' => ['class' => 'checkbox'],
            //'attr' => ['class' => 'checkbox']
        ]);

        $this->add('clone_rounds', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all division rounds?'
        ]);

        $this->add('clone_judges', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all division judges?'
        ]);

        $this->add('submit', 'submit', ['label' => 'Clone Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
