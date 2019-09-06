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
          'rules' => 'unique:users,username,'.$this->model->id
        ]);

				$this->add('password','repeated', [
					'type' => 'password',
					'second_name' => 'password_confirmation',
					'first_options' => [
            'default_value' => '',
						'rules' => 'required|confirmed|min:4'
					],
					'second_options' => [
            'default_value' => '',
						'rules' => 'required'
					],

				]);

        $this->add('is_admin', 'choice', [
          'label' => 'Is User an Carmen Scoring Admin?',
          'choices' => ['1' => 'Admin', '0' => 'Non-Admin'],
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
