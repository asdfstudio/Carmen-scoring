<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\School;

class ChoirForm extends Form
{
    public function buildForm()
    {
				$schools = School::get();

				$this->add('school_id','select', [
					'choices' => $schools->lists('name','id')->toArray(),
					'empty_value' => 'Choose school...'
				]);
        $this->add('name','text', ['rules' => 'required']);
				$this->add('submit', 'submit', ['label' => 'Save Choir', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
