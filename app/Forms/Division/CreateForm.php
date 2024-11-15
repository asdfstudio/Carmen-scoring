<?php

namespace App\Forms\Division;

use App\Round;
use Kris\LaravelFormBuilder\Form;

class CreateForm extends Form
{
    public function buildForm()
    {
        // Add Name field
        $this->add('name', 'text', [
            'label' => 'Name',
            'attr' => [
                'readonly' => 'readonly', // Name is generated dynamically
                'placeholder' => 'Name',
                'class' => 'form-control',
                'id' => 'division-name',
            ],
        ]);

        // Add Drop Down Menu 1 – Type
        $this->add('type', 'choice', [
            'choices' => [
                'Concert Choir' => 'Concert Choir',
                'Chamber/Madrigal Choir' => 'Chamber/Madrigal Choir',
                'Upper Voice Choir' => 'Upper Voice Choir',
                'Lower Voice Choir' => 'Lower Voice Choir',
                'Children’s Choir' => 'Children’s Choir',
                'Gospel Choir' => 'Gospel Choir',
                'Contemporary Vocal Ensemble' => 'Contemporary Vocal Ensemble',
                'Show Choir' => 'Show Choir',
                'Vocal Jazz Choir' => 'Vocal Jazz Choir',
                'Bell Choir' => 'Bell Choir',
                'Concert Band' => 'Concert Band',
                'Jazz Band/Stage Band' => 'Jazz Band/Stage Band',
                'Chamber Orchestra' => 'Chamber Orchestra',
                'String Orchestra' => 'String Orchestra',
                'Full Orchestra' => 'Full Orchestra',
                'Guitar Ensemble' => 'Guitar Ensemble',
                'Percussion Ensemble' => 'Percussion Ensemble',
                'Parade Band/Marching Band' => 'Parade Band/Marching Band',
                'Field Show Combined' => 'Field Show Combined',
                'Field Show Drumline' => 'Field Show Drumline',
                'Field Show Auxiliary' => 'Field Show Auxiliary',
                'Field Show Drum Major' => 'Field Show Drum Major',
                'Parade Combined' => 'Parade Combined',
                'Parade Drumline' => 'Parade Drumline',
                'Parade Auxiliary' => 'Parade Auxiliary',
                'Parade Drum Major' => 'Parade Drum Major',
            ],

            'label' => 'Type',
            'empty_value' => '-- Select Type --',
            'rules' => 'required',
            'attr' => ['id' => 'division-type'],
        ]);


        // Add Drop Down Menu 2 – Class
        $this->add('class', 'choice', [
            'choices' => [
                'C' => 'C',
                'J' => 'J',
                '1A' => '1A',
                '2A' => '2A',
                '3A' => '3A',
                'O' => 'O'
            ],
            'label' => 'Class',
            'empty_value' => '-- Select Class --',
            'rules' => 'required',
            'attr' => ['id' => 'division-class'],
        ]);

        // Add Rating System Dropdown
        $this->add('rating_system_type', 'choice', [
            'choices' => [
                '3A-2A' => '3A-2A',
                '1A-J' => '1A-J',
                'custom' => 'Custom',
            ],
            'label' => 'Select Rating System',
            'empty_value' => '-- Choose Rating System --',
            'attr' => ['id' => 'rating-system-type'],
        ]);

        // Add round selection dropdown
        $competition_id = $this->getData('competition_id');
        $this->add('round_id', 'entity', [
            'class' => 'App\Round',
            'query_builder' => function (Round $round) use ($competition_id) {
                return $round->where('competition_id', $competition_id);
            },
            'property' => 'name',
            'property_key' => 'id',
            'selected' => $this->getData('round_selected'),
            'label' => 'Choose a Type',
            'empty_value' => '-- Select From Available Types --',
            'rules' => 'required',
            'expanded' => false,
            'multiple' => false
        ]);

        // Add the rating system heading
        $this->add('rating_system_heading', 'static', [
            'tag' => 'h2',
            'value' => 'Rating System (optional)',
            'label_show' => false
        ]);

        // Optional rating system fields (for custom input or auto-fill)
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

        // Auto-fill rating system fields based on selected type
        $this->populateRatingFields();

        // Add "Add Another Rating" button
        $this->add('add_rating', 'button', [
            'wrapper' => ['class' => 'add-rating form-group'],
            'attr' => ['class' => 'action'],
            'label' => 'Add Another Rating',
        ]);

        // Add submit button
        $this->add('submit', 'submit', [
            'label' => 'Save Class',
            'attr' => ['class' => 'btn btn-primary createDivision']
        ]);

        // Add Save & Create Another button if it's a new entry
        $is_new = $this->getData('is_new');
        if (isset($is_new)) {
            $this->add('submit_create_another', 'submit', [
                'label' => 'Save & Create Another',
                'attr' => ['class' => 'btn btn-secondary createDivision']
            ]);
        }

        // Add JavaScript to auto-fill based on rating system selection and update the name field
        $this->add('javascript', 'static', [
            'value' => <<<EOT
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('division-type');
            const classSelect = document.getElementById('division-class');
            const nameInput = document.getElementById('division-name');
            const ratingSystemSelect = document.getElementById('rating-system-type');

            function updateNameField() {
                const type = typeSelect.value;
                const classValue = classSelect.value;
                
                // Concatenate selected values and update the name field
                nameInput.value = (type ? type : '') + (classValue ? ' ' + classValue : '');
            }

            // Event listeners for updating the name field when type or class is changed
            typeSelect.addEventListener('change', updateNameField);
            classSelect.addEventListener('change', updateNameField);

            // Auto-fill rating system fields
            const ratingFields3A_2A = [
                {name: 'Gold', minScore: 90},
                {name: 'Silver', minScore: 80},
                {name: 'Bronze', minScore: 65},
                {name: 'Merit', minScore: 50},
                {name: 'Festival', minScore: 1}
            ];

            const ratingFields1A_J = [
                {name: 'Gold', minScore: 85},
                {name: 'Silver', minScore: 75},
                {name: 'Bronze', minScore: 65},
                {name: 'Merit', minScore: 50},
                {name: 'Festival', minScore: 1}
            ];

            ratingSystemSelect.addEventListener('change', function() {
                const selectedType = this.value;
                clearRatingFields(); // Allow for custom entry
                if (selectedType === '3A-2A') {
                    fillRatingFields(ratingFields3A_2A);
                } else if (selectedType === '1A-J') {
                    fillRatingFields(ratingFields1A_J);
                }
            });

            function fillRatingFields(fields) {
                const ratingInputs = document.querySelectorAll('input[name^="rating_system"]');
                ratingInputs.forEach((input, idx) => {
                    const fieldIndex = Math.floor(idx / 2); // Group input by pairs (name, min_score)
                    const field = fields[fieldIndex];
                    if (input.name.includes('name')) {
                        input.value = field.name;
                    } else if (input.name.includes('min_score')) {
                        input.value = field.minScore;
                    }
                });
            }

            function clearRatingFields() {
                document.querySelectorAll('input[name^="rating_system"]').forEach(input => {
                    input.value = '';
                });
            }
        });
    </script>
    EOT,
            'label_show' => false,
        ]);
    }

    // Populate rating fields based on rating system selection
    private function populateRatingFields()
    {
        $ratingFields3A_2A = [
            ['name' => 'Gold', 'minScore' => 90],
            ['name' => 'Silver', 'minScore' => 80],
            ['name' => 'Bronze', 'minScore' => 65],
            ['name' => 'Merit', 'minScore' => 50],
            ['name' => 'Festival', 'minScore' => 1],
        ];

        $ratingFields1A_J = [
            ['name' => 'Gold', 'minScore' => 85],
            ['name' => 'Silver', 'minScore' => 75],
            ['name' => 'Bronze', 'minScore' => 65],
            ['name' => 'Merit', 'minScore' => 50],
            ['name' => 'Festival', 'minScore' => 1],
        ];

        // Get the selected rating system from form data
        $ratingSystemType = $this->getData('rating_system_type');

        // Select fields based on the rating system type
        $fields = [];
        if ($ratingSystemType === '3A-2A') {
            $fields = $ratingFields3A_2A;
        } elseif ($ratingSystemType === '1A-J') {
            $fields = $ratingFields1A_J;
        }

        // Add the rating system fields to the form
        foreach ($fields as $index => $field) {
            $this->add("rating_system[$index][name]", 'text', [
                'label' => 'Rating Name',
                'attr' => [
                    'class' => 'form-control',
                    'value' => $field['name'], // Pre-fill the value
                ],
            ]);

            $this->add("rating_system[$index][min_score]", 'number', [
                'label' => 'Range of Points',
                'attr' => [
                    'class' => 'form-control',
                    'value' => $field['minScore'], // Pre-fill the value
                ],
            ]);
        }
    }
}






// <?php

// namespace App\Forms\Division;

// use App\Round;

// use Kris\LaravelFormBuilder\Form;

// class CreateForm extends Form
// {
//     public function buildForm()
//     {

//         $this->add('name','text', ['rules' => 'required']);

//         $competition_id = $this->getData('competition_id');
//         $this->add('round_id', 'entity', [
//             'class' => 'App\Round',
//             'query_builder' => function(Round $round) use ($competition_id) {
//                 return $round->where('competition_id', $competition_id);
//             },
//             'property' => 'name',
//             'property_key' => 'id',
//             'selected' => $this->getData('round_selected'),
//             'label' => 'Choose a Type',
//             'empty_value' => '-- Select From Available Rounds --',
//             'rules' => 'required',
//             'expanded' => false,
//             'multiple' => false,
//             'choice_options' => [
//                 'wrapper' => ['class' => 'choice-container'],
//                 'labelAttrs' => 'label-class',
//             ]
//         ]);

//             /*
// 				$this->add('sheet_id','entity', [
// 					'class' => 'App\Sheet',
//           'query_builder' => function(\App\Sheet $sheet) {
//             // If query builder option is not provided, all data is fetched
//             return $sheet->where('is_retired', 0);
//           },
//           'label' => 'Scoring Sheet',
//           'choice_options' => [
//             'wrapper' => ['class' => 'choice-container dg-sheet-option-wrapper'],
//             'rules' => 'required'
//           ],
//           'expanded' => true,
//           'multiple' => false
//         ]);

// 				$this->add('caption_weighting_id','entity', [
// 					'class' => 'App\CaptionWeighting',
// 					'empty_value' => 'Choose caption weighting...',
// 					'label' => 'Caption Weighting',
//           'label_attr' => ['class' => 'block'],
//           //'property' => 'full_name',
//           'expanded' => true,
//           'multiple' => false,
//           'choice_options' => [
//             'wrapper' => ['class' => 'choice-container'],
//             'rules' => 'required'
//           ],
//           'help_block' => [
//             'text' => ''
//           ]
// 				]);

//         // When listing scoring methods, leave out ID 2 (Ranked Scores) unless it is already chosen for this division.
//         $selected_scoring_method = !empty($this->model) && !empty($this->model->scoring_method_id) ? $this->model->scoring_method_id : '';
//         if($selected_scoring_method !== 2){
//           $scoring_methods = \App\ScoringMethod::where('id', '!=', 2)->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
//         } else {
//           $scoring_methods = \App\ScoringMethod::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
//         }
//         //dd($scoring_methods);
//         //dd($this->model->scoring_method_id);

// 				$this->add('scoring_method_id','choice', [
//           //'class' => 'App\ScoringMethod',
//           'choices' => $scoring_methods,
//           'selected' => $selected_scoring_method,
// 					'empty_value' => 'Choose scoring method...',
// 					'label' => 'Scoring Method',
//           'label_attr' => ['class' => 'block'],
//           'expanded' => true,
//           'multiple' => false,
//           'choice_options' => [
//             'wrapper' => ['class' => 'choice-container'],
//             'rules' => 'required'
//           ],
//           'help_block' => [
//             //'text' => 'The Ranked scoring method should be used only if at least one of the following is true: 1) The Caption Weighting is 50/50. 2) All judges are scoring both the Music and Show captions. 3) There are 50% more judges scoring the Music caption than the Show caption.'
//           ]
// 				]);
//              */

//         /*$this->add('award_heading', 'static', [
//           'tag' => 'h2',
//           'value' => 'Award Settings',
//           'label_show' => false
//         ]);

//         $this->add('overall_award_count','number', [
//           'rules' => 'required',
//           'help_block' => [
//             'text' => 'How many choirs will receive Overall Awards?'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => 0,
//           'attr' => ['min' => 0]
//         ]);

//         $this->add('music_award_count','number', [
//           'rules' => 'required',
//           'help_block' => [
//             'text' => 'How many choirs will receive Music Awards?'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => 0,
//           'attr' => ['min' => 0]
//         ]);

//         $this->add('show_award_count','number', [
//           'rules' => 'required',
//           'help_block' => [
//             'text' => 'How many choirs will receive Show Awards?'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => 0,
//           'attr' => ['min' => 0]
//         ]);

//         $this->add('combo_award_count','number', [
//           'rules' => 'required',
//           'help_block' => [
//             'text' => 'How many choirs will receive Combo Awards?'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => 0,
//           'attr' => ['min' => 0]
//         ]);


//         $this->add('overall_award_sponsors','textarea', [
//           'help_block' => [
//             'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ]
//         ]);


//         $this->add('music_award_sponsors','textarea', [
//           'help_block' => [
//             'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => ''
//         ]);

//         $this->add('show_award_sponsors','textarea', [
//           'help_block' => [
//             'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => ''
//         ]);

//         $this->add('combo_award_sponsors','textarea', [
//           'help_block' => [
//             'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
//           ],
//           'wrapper' => [
//             'class' => 'form-group col-md-3 col-xs-12'
//           ],
//           'default_value' => ''
//         ]);*/



//         $this->add('rating_system_heading', 'static', [
//           'tag' => 'h2',
//           'value' => 'Rating System (optional)',
//           'label_show' => false
//         ]);

//         $i = 0;
//         $maxRatingSystemSets = 4;

//         $this->add('rating_system', 'collection', [
//           'type' => 'form',
//           'label_show' => false,
//           'prototype' => true,
//           'prototype_name' => '__NAME__',
//           'options' => [
//             'class' => 'Division\RatingsForm',
//             'label_show' => false
//           ]
//         ]);

//         $this->add('add_rating', 'button', [
//           'wrapper' => ['class' => 'add-rating form-group'],
//           'attr' => ['class' => 'action'],
//           'label' => 'Add Another Rating',
//         ]);

// /*
//         while($i < $maxRatingSystemSets)
//         {

//           if($this->model && isset($this->model->rating_system[$i]))
//           {
//             $nameValue = $this->model->rating_system[$i]['name'];
//             $minScoreValue = $this->model->rating_system[$i]['min_score'];
//           }
//           else {
//             $nameValue = false;
//             $minScoreValue = false;
//           }

//           $this->add('rating_system['.$i.'][name]', 'text', [
//             'label' => 'Rating Name',
//             'default_value' => $nameValue,
//             'wrapper' => [
//               'class' => 'form-group col-md-6 col-xs-12'
//             ],
//           ]);

//           $this->add('rating_system['.$i.'][min_score]', 'number', [
//             'label' => 'Minimum % of Total Available Score',
//             'attr' => [
//               'min' => 0,
//               'max' => 100
//             ],
//             'default_value' => $minScoreValue,
//             'wrapper' => [
//               'class' => 'form-group col-md-6 col-xs-12'
//             ],
//           ]);

//           $i++;
//         }
// */

// 		$this->add('submit', 'submit', [
//           'label' => 'Save Division',
//           'value' => 'submit',
//           'attr' => ['class' => 'btn btn-primary createDivision', 'name' => 'submit']
//         ]);

//         $is_new = $this->getData('is_new');
//         if(isset($is_new)) {
//             $this->add('submit_create_another', 'submit', [
//                 'label' => 'Save & Create Another',
//                 'attr' => ['class' => 'btn btn-secondary createDivision', 'name' => 'submit_create_another']
//             ]);
//         }
//     }
// }
