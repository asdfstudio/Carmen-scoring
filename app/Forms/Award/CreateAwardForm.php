<?php

namespace App\Forms\Award;

use Kris\LaravelFormBuilder\Form;

class CreateAwardForm extends Form
{
    public function buildForm()
    {

        $this->add('name','text', [
          'rules' => 'required'
        ]);

        $this->add('description','textarea', [
            'attr' => ['rows' => 3]
        ]);

        if(isset($this->data['include_sponsor']))
        {
          $this->add('sponsor','text', [
              'attr' => ['rows' => 3]
          ]);
        }

        $this->add('submit', 'submit', [
          'label' => 'Save Award',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Create Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
    }
}
