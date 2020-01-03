<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class GenericDeleteForm extends Form
{
    protected $formOptions = [
      'method' => 'DELETE',
      'class' => 'form-inline',
      'button_text' => 'Delete'
    ];

    public function buildForm()
    {
				$this->add('submit', 'submit', [
          'label' => $this->formOptions['button_text'],
          'attr' => ['class' => 'action danger']
        ]);
    }
}
