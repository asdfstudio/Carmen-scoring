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
          'label' => 'School That the Ensemble Belongs To',
					'choices' => $schools->pluck('name','id')->toArray(),
					'empty_value' => 'Choose school...',
          'attr' => ['class' => 'selectize']
				]);
        $this->add('name','text', [
          'label' => 'Ensemble Name',
          'rules' => 'required'
        ]);
				$this->add('submit', 'submit', ['label' => 'Save Ensemble', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
