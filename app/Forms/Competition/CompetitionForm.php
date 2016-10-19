<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class CompetitionForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);
				$this->add('place','form', [
					'class' => 'PlaceForm'
				]);
				$this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
