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
          'rules' => ''
        ]);

        if(isset($this->data['include_sponsor']))
        {
          $this->add('sponsor','text', [
            'rules' => ''
          ]);
        }


        //$this->add('submit', 'submit', ['label' => 'Save Award', 'attr' => ['class' => 'btn btn-primary']]);

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
