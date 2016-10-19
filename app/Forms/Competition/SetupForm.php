<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class SetupForm extends Form
{
    public function buildForm()
    {
      //$this->add('name','text', ['rules' => 'required', 'label' => 'Competition Name']);
    //  $this->add('place','form', [
        //'class' => 'App\Forms\PlaceForm',
        //'label' => 'Location'
      //]);

      $this->add('divisions', 'collection', [
                'type' => 'form',
                'options' => [    // these are options for a single type
                    'class' => 'Division\CreatePrototypeForm',
                    'label' => false,
                ],
                //'template' => 'forms.checkbox' // not working
            ]);

      $this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
