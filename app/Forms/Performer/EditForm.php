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

        $soloDivision = $this->getData('soloDivision');

        $this->add('category','choice', [
          'rules' => 'required',
          'choices' => [1 => $soloDivision->category_1, 2 => $soloDivision->category_2],
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
