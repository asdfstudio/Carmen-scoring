<?php

namespace App\Forms\Judge;

use Kris\LaravelFormBuilder\Form;

class ChooseJudgeForm extends Form
{
    protected $formOptions = [
      'class' => 'add-resource-form-prototype add-resource-form',
      'data-resource-type' => 'judge'
    ];

    public function buildForm()
    {
        /*$this->add('judge_id','entity', [
					'class' => 'App\Judge',
					'empty_value' => 'Choose judge...',
					'label' => 'Choose from existing judges',
					'property' => 'first_name',
          'rules' => ['required_without:judge.first_name'],
          'wrapper' => ['class' => 'existing_judge_container']
				]);*/

        $this->add('judge_id','choice', [
					'choices' => $this->data,
					//'empty_value' => 'Choose judge...',
					'label' => 'Choose from existing judges',
          'attr' => ['class' => 'judge_id', 'id' => ''],
					//'property' => 'first_name',
          'rules' => ['required_without:judge.first_name'],
          'wrapper' => ['class' => 'existing_judge_container']
				]);

        $this->add('add_new_judge','static', [
          'tag' => 'a',
          'attr' => ['class' => 'toggle-new-judge-container btn btn-secondary'],
          'value' => 'Create a new judge',
          'label_show' => false
        ]);

        $this->add('judge', 'form', [
          'class' => $this->formBuilder->create('Judge\CreateJudgeForm'),
          'wrapper' => ['class' => 'new_judge_container'],
          'label_show' => false
        ]);

				$this->add('caption_id','entity', [
					'class' => 'App\Caption',
					'empty_value' => 'Choose caption ...',
					'label' => 'Captions to Score',
          'rules' => ['filled'],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
            'labelAttrs' => 'label-attr'
          ],
					'expanded' => true,
					'multiple' => true
				]);

				//$this->add('submit', 'submit', ['label' => 'Save Judge', 'attr' => ['class' => 'btn btn-primary']]);

        $this->add('submit', 'submit', [
          'label' => 'Save Judge',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);
        
        /*
        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Add Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
        */
    }
}
