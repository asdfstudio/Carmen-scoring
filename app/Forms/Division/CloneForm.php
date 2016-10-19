<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class CloneForm extends Form
{
    public function buildForm()
    {
        $this->add('division_name', 'text', [
          //'default_value' => $division->name,
        ]);

        $this->add('clone_rounds', 'checkbox', [
            'value' => 1,
            'checked' => false
        ]);

        $this->add('clone_judges', 'checkbox', [
            'value' => 1,
            'checked' => false
        ]);

        $this->add('submit', 'submit', ['label' => 'Clone Division', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
