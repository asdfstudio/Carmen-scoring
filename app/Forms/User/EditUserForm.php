<?php

namespace App\Forms\User;

use Kris\LaravelFormBuilder\Form;

class EditUserForm extends Form
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
						'rules' => 'confirmed|min:4',
						'label' => 'New Password (leave blank to keep current password)',
						'value' => false
					],
					'second_options' => [
						'rules' => 'required_with:password',
						'label' => 'New Password Confirmation'
					]
				]);

        $this->add('organization_role', 'choice', [
          'choices' => ['admin' => 'Administrator', 'standard' => 'Standard User']
        ]);

				$this->add('submit', 'submit', ['label' => 'Update User', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
