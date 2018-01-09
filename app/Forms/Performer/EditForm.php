<?php

namespace App\Forms\Performer;

use Kris\LaravelFormBuilder\Form;

class EditForm extends Form
{
    protected $formOptions = [
      'method' => 'post'
    ];

    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);

        $this->add('gender','choice', [
          'rules' => 'required',
          'choices' => ['F' => 'Female', 'M' => 'Male'],
          'expanded' => false,
          'multiple' => false
        ]);

        $this->add('submit', 'submit', [
          'label' => 'Save',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
    }
}
