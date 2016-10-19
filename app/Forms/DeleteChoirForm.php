<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class DeleteChoirForm extends Form
{
    public function buildForm()
    {
				$this->add('submit', 'submit', ['label' => 'Remove Choir', 'attr' => ['class' => 'btn btn-danger']]);
    }
}
