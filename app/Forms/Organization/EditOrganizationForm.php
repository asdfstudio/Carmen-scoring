<?php

namespace App\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class EditOrganizationForm extends Form
{
    public function buildForm()
    {
      $this->add('name','text', ['rules' => 'required']);

      $this->add('place','form', [
        'class' => 'Place\PlaceForm',
        'label_show' => false
      ]);

      $this->add('submit', 'submit', ['label' => 'Save Organization', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
