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
          'label' => 'Begin Date (YYYY-MM-DD)'
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
            'wrapper' => ['class' => 'choice-container']
          ],
          'expanded' => true,
          'multiple' => false,
          'label' => 'Results Naming'
        ]);

        /*$this->add('rating_system', 'textarea', [
          'help_block' => [
            'text' => 'Enter the name of the rating and the minimum percent. One per line'
          ]
        ]);*/

        $i = 0;
        $maxRatingSystemSets = 3;

        $this->add('rating_system_heading', 'static', [
          'tag' => 'h2',
          'value' => 'Rating System (optional)',
          'label_show' => false
        ]);

        while($i < $maxRatingSystemSets)
        {

          if($this->model)
          {
            $nameValue = $this->model->rating_system[$i]['name'];
            $minScoreValue = $this->model->rating_system[$i]['min_score'];
          }
          else {
            $nameValue = false;
            $minScoreValue = false;
          }

          $this->add('rating_system['.$i.'][name]', 'text', [
            'label' => 'Rating Name',
            'default_value' => $nameValue,
            'wrapper' => [
              'class' => 'form-group col-md-6 col-xs-12'
            ],
          ]);

          $this->add('rating_system['.$i.'][min_score]', 'number', [
            'label' => 'Minimum Score',
            'attr' => [
              'min' => 0,
              'max' => 100
            ],
            'default_value' => $minScoreValue,
            'wrapper' => [
              'class' => 'form-group col-md-6 col-xs-12'
            ],
          ]);

          $i++;
        }


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
