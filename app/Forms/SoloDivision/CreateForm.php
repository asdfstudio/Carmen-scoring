<?php

namespace App\Forms\SoloDivision;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
    protected $formOptions = [
      'method' => 'POST'
    ];

    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required']);

        $this->add('sheet_id','entity', [
					'class' => 'App\Sheet',
					'empty_value' => 'Choose scoring sheet...',
					'label' => 'Scoring Sheet',
          'label_attr' => ['class' => 'block'],
          'expanded' => false,
          'multiple' => false,
          //'wrapper' => ['class' => 'wrap'],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
            'labelAttrs' => 'label-attr'
          ]
				]);

        $this->add('max_performers','number', [
          'rules' => 'required',
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);

        $this->add('category_1','text', [
          'label' => 'Category #1 Name'
        ]);

        $this->add('category_2','text', [
          'label' => 'Category #2 Name'
        ]);


        $judge1 = false;
        $judge2 = false;

        if ($this->model AND $this->model->judges->count() > 0) {
          $judge1 = $this->model->judges->pluck('id')->first();
          $judge2 = $this->model->judges->slice(1,1)->pluck('id')->first();
        }

        $this->add('judge_id[0]','choice', [
					'choices' => $this->data['judges'],
					'empty_value' => 'Choose judge...',
					'label' => 'Choose judge #1 (required)',
          'rules' => ['required'],
          'default_value' => $judge1
				]);

        $this->add('judge_id[1]','choice', [
					'choices' => $this->data['judges'],
					'empty_value' => 'Choose judge...',
					'label' => 'Choose judge #2 (optional)',
          'rules' => [],
          'default_value' => $judge2
				]);

        $this->add('submit', 'submit', [
          'label' => 'Save Solo Division',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
    }
}
