<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PersonForm extends Form
{
    public function buildForm()
    {
        $this->add('first_name','text', ['rules' => 'required']);
				$this->add('last_name','text', ['rules' => 'required']);
				$this->add('email','email', ['rules' => 'required']);
				$this->add('submit', 'submit', ['label' => 'Save Person', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
