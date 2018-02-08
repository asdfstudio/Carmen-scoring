<?php

namespace App\Forms\SoloDivision;

use Kris\LaravelFormBuilder\Form;

class AccessCodeForm extends Form
{
    public function buildForm()
    {
        $this->add('access_code','text', ['rules' => 'required']);

        $this->add('submit', 'submit', [
          'label' => 'Access Full Results',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
    }
}
