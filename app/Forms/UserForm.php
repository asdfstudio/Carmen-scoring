<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class UserForm extends Form
{
    public function buildForm()
    {
        $this->add('email','email', ['rules' => 'required']);
				$this->add('submit', 'submit', ['label' => 'Save User', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
