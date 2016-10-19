<?php

namespace App\Forms\Place;

use Kris\LaravelFormBuilder\Form;

class PlaceForm extends Form
{
    public function buildForm()
    {
        $this->add('address','text'/*, ['rules' => 'required']*/);
				$this->add('address_2','text'/*, ['rules' => 'required']*/);
        $this->add('city','text', ['rules' => 'required']);
				$this->add('state','text', ['rules' => 'required']);
				$this->add('postal_code','text'/*, ['rules' => 'required']*/);
				//$this->add('submit', 'submit', ['label' => 'Save Place', 'attr' => ['class' => 'btn']]);
    }
}
