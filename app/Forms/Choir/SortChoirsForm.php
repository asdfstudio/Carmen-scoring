<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

class SortChoirsForm extends Form
{
    protected $formOptions = [
      'method' => 'POST',
      'class' => 'sortable',
      'id' => 'sortable-form'
    ];

    public function buildForm()
    {
        //dd($this->model);

        foreach($this->model as $choir)
        {
          $this->add('performance_order['.$choir->id.']', 'text', [
            'default_value' => $choir->pivot->performance_order,
            //'label_show' => false,
            'label' => $choir->full_name,
            'wrapper' => [
              'class' => 'sortable',
              'data-id' => $choir->id
            ]
          ]);
        }

        $this->add('submit', 'submit', ['label' => 'Save Performance Order', 'attr' => ['class' => 'btn btn-primary not-sortable']]);
    }
}
