<?php

namespace App\Forms\Place;

use Kris\LaravelFormBuilder\Form;

class ShortPlaceForm extends Form
{
    public function buildForm()
    {
      $this->add('city','text', [
        //'rules' => ['required_with:school.name']
      ]);
      $this->add('state','text', [
        //'rules' => ['required_with:school.name']
      ]);
    }
}
