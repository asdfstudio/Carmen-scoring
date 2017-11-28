<?php

namespace App\Forms\AwardSchedule;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
    public function buildForm()
    {
				$this->add('name','text', ['rules' => 'required']);

				$this->add('submit', 'submit', [
          'label' => 'Save Schedule',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

    }
}
