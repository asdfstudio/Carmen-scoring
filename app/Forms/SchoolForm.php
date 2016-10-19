<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class SchoolForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);
				$this->add('place','form', [
					'class' => 'PlaceForm'
				]);
				$this->add('submit', 'submit', ['label' => 'Save School', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
