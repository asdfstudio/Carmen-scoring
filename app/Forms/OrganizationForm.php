<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class OrganizationForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);
				//$this->add('person','form', [
					//'class' => 'PersonForm'
				//]);

        $this->add('place', 'form', [
          'class' => $this->formBuilder->create('Place\PlaceForm'),
          'label_show' => false
        ]);

				$this->add('submit', 'submit', ['label' => 'Save Organization', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
