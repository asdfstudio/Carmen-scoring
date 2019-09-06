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

        $this->add('email','email', [
          'rules' => 'required|email|unique:users,email,'.$this->model->id
        ]);

        $this->add('emails_additional','email', [
          'label' => 'Additional Email Addresses',
          'default_value' => $this->getModel()->person->emails_additional,
          'help_block' => [
            'text' => 'One or more addition emails that should also get notifications. Separate addresses with a comma.',
            'tag' => 'p',
            'attr' => ['class' => 'help-block']
          ]
        ]);

        $this->add('username','text', [
          'rules' => 'required|unique:users,username,'.$this->model->id,
          'label' => 'Username'
        ]);

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
