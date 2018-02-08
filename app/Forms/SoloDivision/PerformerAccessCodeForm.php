<?php

namespace App\Forms\SoloDivision;

use Kris\LaravelFormBuilder\Form;

class PerformerAccessCodeForm extends Form
{
    public function buildForm()
    {
        $this->add('director_email', 'email', ['rules' => 'required']);

        $this->add('submit', 'submit', [
          'label' => 'Access Full Results',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
    }
}
