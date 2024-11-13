<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CloneForm extends Form
{
    public function buildForm()
    {

        $this->add('competition_name', 'text', [
          'default_value' => $this->data['competition_name'].' - Copy',
        ]);

        $this->add('clone_rounds', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all class typess?'
        ]);

        $this->add('clone_divisions', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all classes?',
            //'wrapper' => ['class' => 'checkbox'],
            //'attr' => ['class' => 'checkbox']
        ]);

        $this->add('clone_judges', 'checkbox', [
            'value' => 1,
            'checked' => false,
            'label' => 'Clone all class judges?'
        ]);

        $this->add('submit', 'submit', ['label' => 'Clone Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
