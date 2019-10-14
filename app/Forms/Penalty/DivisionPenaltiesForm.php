<?php

namespace App\Forms\Penalty;

use Kris\LaravelFormBuilder\Form;

use Auth;

use App\Penalty;

class DivisionPenaltiesForm extends Form
{
    //protected $selected_penalties = false;

    //public function __construct($selected_penalties = false)
    //{
    //  $this->selected_penalties = $selected_penalties;
    //}

    public function buildForm()
    {
        $this->add('penalties', 'entity', [
          'class' => 'App\Penalty',
          'property' => 'name',
          'choice_options' => [
            'wrapper' => ['class' => 'choice-wrapper'],
            'labelAttrs' => 'label-attr'
        ],
          'expanded' => true,
          'multiple' => true,
         
          'query_builder' => function (\App\Penalty $query) {
            return $query->where('organization_id', Auth::user()->organization_id);
          }
        ]);

        $this->add('submit', 'submit', ['label' => 'Save Penalties', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
