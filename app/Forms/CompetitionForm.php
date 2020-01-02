<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Organization;

class CompetitionForm extends Form
{
    public function buildForm()
    {
        $organizations = Organization::get();

				$this->add('organization_id','select', [
					'choices' => $organizations->pluck('name','id')->toArray(),
					'empty_value' => 'Choose organization...'
				]);
        $this->add('name','text', ['rules' => 'required']);
				$this->add('place','form', [
					'class' => 'App\Forms\PlaceForm'
				]);
				$this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
