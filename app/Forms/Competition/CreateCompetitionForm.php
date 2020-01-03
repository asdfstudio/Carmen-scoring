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

        $this->add('begin_date','text', [
          'rules' => '',
          'label' => 'Begin Date (YYYY-MM-DD)',
        ]);

        $this->add('end_date','text', [
          'rules' => '',
          'label' => 'End Date (YYYY-MM-DD)'
        ]);
        $this->add('use_runner_up_names', 'choice', [
          'choices' => [
            0 => '1st, 2nd, 3rd...',
            1 => 'Grand Champion, 1st Runner Up, 2nd Runner Up...'
          ],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
              'labelAttrs' => 'label-attr'
          ],
          'label' => 'Results Naming',
          'expanded' => true,
          'multiple' => false,
      ]);

        $this->add('place_heading', 'static', [
          'tag' => 'h2',
          'value' => 'Location',
          'label_show' => false
        ]);

				$this->add('place','form', [
					'class' => 'PlaceForm',
					'label' => 'Location',
          'label_show' => false
				]);
				$this->add('submit', 'submit', ['label' => 'Save Competition', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
