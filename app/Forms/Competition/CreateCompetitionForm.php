<?php

namespace App\Forms\Competition;

use Kris\LaravelFormBuilder\Form;

class CreateCompetitionForm extends Form
{
    public function buildForm()
    {
        $this->add('name','text', ['rules' => 'required', 'label' => 'Competition Name']);

        $this->add('slug','text', [
          'rules' => '',
          'label' => 'Results URL Slug'
        ]);

        $this->add('access_code','text', [
          'rules' => '',
          'label' => 'Results Access Code'
        ]);

        $this->add('use_runner_up_names', 'choice', [
          'choices' => [
            0 => '1st, 2nd, 3rd...',
            1 => 'Grand Champion, 1st Runner Up, 2nd Runner Up...'
          ],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container']
          ],
          'expanded' => true,
          'multiple' => false,
          'label' => 'Results Naming'
        ]);

				$this->add('place','form', [
					'class' => 'PlaceForm',
					'label' => 'Location'
				]);
				$this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
