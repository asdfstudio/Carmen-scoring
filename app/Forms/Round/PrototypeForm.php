<?php

namespace App\Forms\Round;

use Kris\LaravelFormBuilder\Form;

class PrototypeForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);
    }
}
