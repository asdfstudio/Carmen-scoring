<?php

namespace App\Forms\Judge;

use App\Judge;
use DB;

use Kris\LaravelFormBuilder\Form;

class ChooseSoloDivisionJudgesForm extends Form
{
    protected $formOptions = [
        'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('judges', 'collection', [
            'type' => 'form',
            'options' => [
                'class' => 'Judge\JudgesDropdownForm',
                'label' => false,
                'wrapper' => [
                    'class' => 'judge-row form-group'
                ],
            ],
            'prototype' => TRUE,
            'prototype_name' => '__NAME__',
            'prefer_input' => TRUE,
            'label_show' => false,
        ]);

        $this->add('add_judge', 'button', [
            'wrapper' => ['class' => 'add-judge form-group'],
            'attr' => ['class' => 'action'],
            'label' => 'Add Another Judge',
        ]);

        $this->add('submit', 'submit', [
            'label' => 'Save Judges',
            'value' => 'submit',
            'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);


    }
}

class JudgesDropdownForm extends form
{
    public function buildForm()
    {
        $this->add('id', 'entity', [
            'class' => Judge::class,
            'query_builder' => function(Judge $judge) {
                return $judge::select('id')
                    ->addSelect(DB::raw('CONCAT(first_name, \' \', last_name) AS name'));

            },
            'label_show' => FALSE,
            'empty_value' => 'Choose judge...',
            'property' => 'name',
            'property_key' => 'id',
            'wrapper' => [
                'class' => 'form-group col-md-11 col-xs-11'
            ],
        ]);

        $this->add('remove_judge', 'button', [
            'label' => 'X',
            'wrapper' => [
                'class' => 'remove-judge form-group col-md-1 col-xs-1'
            ],
        ]);

    }
}
