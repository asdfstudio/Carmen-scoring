<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CreateCompetitionForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required', 'label' => 'Competition Name']);
				$this->add('place','form', [
					'class' => 'PlaceForm',
					'label' => 'Location'
				]);
				$this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
