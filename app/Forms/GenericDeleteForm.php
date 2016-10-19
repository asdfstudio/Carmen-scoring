<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class GenericDeleteForm extends Form
{
    protected $formOptions = [
      'method' => 'DELETE',
      'class' => 'form-inline'
    ];

    public function buildForm()
    {
				$this->add('submit', 'submit', [
          'label' => 'Delete',
          'attr' => ['class' => 'action danger'],
          //'template' => ''
        ]);
    }
}
