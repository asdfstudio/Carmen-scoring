<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class DeleteChoirForm extends Form
{
    public function buildForm()
    {
		  $this->add('submit', 'submit', ['label' => 'Remove ensemble', 'attr' => ['class' => 'btn btn-danger']]);
    }
}
