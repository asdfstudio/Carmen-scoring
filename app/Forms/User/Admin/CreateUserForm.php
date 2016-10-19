<?php

namespace App\Forms\User\Admin;

use Kris\LaravelFormBuilder\Form;

class CreateUserForm extends Form
{
    public function buildForm()
    {
        $this->add('person[first_name]', 'text', [
          'rules' => 'required',
          'label' => 'First Name'
        ]);
        $this->add('person[last_name]', 'text', [
          'rules' => 'required',
          'label' => 'Last Name'
        ]);

        $this->add('email','email', ['rules' => 'required']);

				$this->add('password','repeated', [
					'type' => 'password',
					'second_name' => 'password_confirmation',
					'first_options' => [
						'rules' => 'required|confirmed|min:4'
					],
					'second_options' => [
						'rules' => 'required'
					]
				]);

        $this->add('is_admin', 'choice', [
          'label' => 'Is User an Carmen Showchoir Admin?',
          'choices' => ['1' => 'Administrator', '0' => 'Standard User'],
          'empty_value' => 'Choose role...'
        ]);

        $this->add('organization_id','entity', [
          'class' => 'App\Organization',
          'empty_value' => 'Choose organization...',
          'label' => 'Organization',
          'rules' => []
        ]);

        $this->add('organization_role', 'choice', [
          'choices' => ['admin' => 'Administrator', 'standard' => 'Standard User'],
          'empty_value' => 'Choose role...'
        ]);

				$this->add('submit', 'submit', ['label' => 'Create User', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
