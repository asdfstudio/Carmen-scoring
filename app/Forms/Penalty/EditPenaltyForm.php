<?php

namespace App\Forms\Penalty;

use Kris\LaravelFormBuilder\Form;

class EditPenaltyForm extends Form
{
    public function buildForm()
    {
        $this->add('submit', 'submit', ['label' => 'Update Penalty', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
