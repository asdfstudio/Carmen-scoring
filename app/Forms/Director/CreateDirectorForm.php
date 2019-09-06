<?php

namespace App\Forms\Director;

use Kris\LaravelFormBuilder\Form;
use App\Person;

class CreateDirectorForm extends Form
{
  protected $formOptions = [
    'method' => 'POST'
  ];

  public function buildForm()
  {
    
    $this->add('person_search','text', [
      'label' => 'Find Existing Director',
      'attr' => ['id' => 'person-search'],
      'wrapper' => ['class' => 'search-group']
    ]);
    
    $this->add('person_id','hidden', [
      'label_show' => false,
      'attr' => ['id' => 'person-id']
    ]);
    
    $this->add('add_director', 'static', [
      'label_show' => false,
      'tag' => 'a',
      'attr' => ['class' => 'add-new', 'href' => '#'],
      'value' => 'Add New Director',
      'wrapper' => ['class' => 'search-group']
    ]);
      
    $this->add('first_name','text', [
      'rules' => 'required',
      'label' => 'First Name'
    ]);

    $this->add('last_name','text', [
      'rules' => 'required',
      'label' => 'Last Name'
    ]);

    /*if($this->model)
    {
      $rules = 'required|email|unique:people,email,'.$this->model->id;
    }
    else {
      $rules = 'required|email|unique:people,email';
    }*/

    $rules = 'required|email';


    $this->add('email','text', [
      'rules' => $rules,
      'label' => 'Primary Email Address'
    ]);

    $this->add('emails_additional','text', [
      'rules' => 'email',
      'label' => 'Additional Email Addresses',
      'help_block' => [
        'text' => 'One or more addition emails that should also get notifications. Separate addresses with a comma.',
        'tag' => 'p',
        'attr' => ['class' => 'help-block']
      ]
    ]);

    $this->add('tel','text', [
      'rules' => '',
      'label' => 'Mobile Phone Number (to receive link to results via text message)'
    ]);


    $this->add('submit', 'submit', ['label' => 'Save Director', 'attr' => ['class' => 'btn btn-primary']]);
  }
}
