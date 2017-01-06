<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class ArchiveForm extends Form
{
    protected $formOptions = [
      //'class' => 'pull-left',
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('archive','hidden',['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Archive Competition', 'attr' => ['class' => 'action']]);
    }
}
