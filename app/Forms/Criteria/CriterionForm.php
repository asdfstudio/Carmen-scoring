<?php

namespace App\Forms\Criteria;

use Kris\LaravelFormBuilder\Form;

class CriterionForm extends Form
{
  protected $formOptions = [

  ];

  public function buildForm()
  {
      $this->add('caption_id','select', [
        'rules' => 'required',
        'label' => 'Caption',
        'choices' => $this->data['captions']
      ]);

      $this->add('name','text', [
        'rules' => 'required',
        'label' => 'Name'
      ]);

      $this->add('description','textarea', [
        'rules' => '',
        'label' => 'Description'
      ]);

      $this->add('allow_fractional','choice', [
        'label' => 'Allow score points with 0.5s',
        'choices' => [1 => 'Allow', 0 => 'Restrict'],
      ]);

      $choices = array_combine(range(1,100), range(1,100));

      $this->add('max_score', 'choice', [
        'expanded' => false,
        'multiple' => false,
        'choices' => $choices,
        'defaultValue' => 10
      ]);


      $this->add('submit', 'submit', [
        'label' => 'Save Criterion',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);
  }
}
