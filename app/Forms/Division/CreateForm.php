<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
    public function buildForm()
    {

				$this->add('name','text', ['rules' => 'required']);

				$this->add('caption_weighting_id','entity', [
					'class' => 'App\CaptionWeighting',
					'empty_value' => 'Choose caption weighting...',
					'label' => 'Caption Weighting',
          'label_attr' => ['class' => 'block'],
          //'property' => 'full_name',
          'expanded' => true,
          'multiple' => false,
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container']
          ],
          'help_block' => [
            'text' => ''
          ]
				]);
        
        // When listing scoring methods, leave out ID 2 (Ranked Scores) unless it is already chosen for this division.
        $selected_scoring_method = !empty($this->model) && !empty($this->model->scoring_method_id) ? $this->model->scoring_method_id : '';
        if($selected_scoring_method !== 2){
          $scoring_methods = \App\ScoringMethod::where('id', '!=', 2)->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        } else {
          $scoring_methods = \App\ScoringMethod::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }
        //dd($scoring_methods);
        //dd($this->model->scoring_method_id);
        
				$this->add('scoring_method_id','choice', [
          //'class' => 'App\ScoringMethod',
          'choices' => $scoring_methods,
          'selected' => $selected_scoring_method,
					'empty_value' => 'Choose scoring method...',
					'label' => 'Scoring Method',
          'label_attr' => ['class' => 'block'],
          'expanded' => true,
          'multiple' => false,
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container']
          ],
          'help_block' => [
            //'text' => 'The Ranked scoring method should be used only if at least one of the following is true: 1) The Caption Weighting is 50/50. 2) All judges are scoring both the Music and Show captions. 3) There are 50% more judges scoring the Music caption than the Show caption.'
          ]
				]);

				$this->add('sheet_id','entity', [
					'class' => 'App\Sheet',
          'query_builder' => function(\App\Sheet $sheet) {
            // If query builder option is not provided, all data is fetched
            return $sheet->where('is_retired', 0);
          },
					'empty_value' => 'Choose scoring sheet...',
					'label' => 'Scoring Sheet',
          'label_attr' => ['class' => 'block'],
          'expanded' => true,
          'multiple' => false,
          //'wrapper' => ['class' => 'wrap'],
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container']
          ]
				]);

        /*$this->add('award_heading', 'static', [
          'tag' => 'h2',
          'value' => 'Award Settings',
          'label_show' => false
        ]);

        $this->add('overall_award_count','number', [
          'rules' => 'required',
          'help_block' => [
            'text' => 'How many choirs will receive Overall Awards?'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);

        $this->add('music_award_count','number', [
          'rules' => 'required',
          'help_block' => [
            'text' => 'How many choirs will receive Music Awards?'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);

        $this->add('show_award_count','number', [
          'rules' => 'required',
          'help_block' => [
            'text' => 'How many choirs will receive Show Awards?'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);

        $this->add('combo_award_count','number', [
          'rules' => 'required',
          'help_block' => [
            'text' => 'How many choirs will receive Combo Awards?'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => 0,
          'attr' => ['min' => 0]
        ]);


        $this->add('overall_award_sponsors','textarea', [
          'help_block' => [
            'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ]
        ]);


        $this->add('music_award_sponsors','textarea', [
          'help_block' => [
            'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => ''
        ]);

        $this->add('show_award_sponsors','textarea', [
          'help_block' => [
            'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => ''
        ]);

        $this->add('combo_award_sponsors','textarea', [
          'help_block' => [
            'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
          ],
          'wrapper' => [
            'class' => 'form-group col-md-3 col-xs-12'
          ],
          'default_value' => ''
        ]);*/

      
      
        $this->add('rating_system_heading', 'static', [
          'tag' => 'h2',
          'value' => 'Rating System (optional)',
          'label_show' => false
        ]);

        $i = 0;
        $maxRatingSystemSets = 4;
        
        $this->add('rating_system', 'collection', [
          'type' => 'form',
          'label_show' => false,
          'prototype' => true,
          'prototype_name' => '__NAME__',
          'options' => [
            'class' => 'Division\RatingsForm',
            'label_show' => false
          ]
        ]);

        $this->add('add_rating', 'button', [
          'wrapper' => ['class' => 'add-rating form-group'],
          'attr' => ['class' => 'action'],
          'label' => 'Add Another Rating',
        ]);

/*
        while($i < $maxRatingSystemSets)
        {

          if($this->model && isset($this->model->rating_system[$i]))
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
            'label' => 'Minimum % of Total Available Score',
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
*/

				$this->add('submit', 'submit', [
          'label' => 'Save Division',
          'value' => 'submit',
          'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
        ]);

        $this->add('submit_create_another', 'submit', [
          'label' => 'Save & Create Another',
          'attr' => ['class' => 'btn btn-secondary', 'name' => 'submit_create_another']
        ]);
    }
}
