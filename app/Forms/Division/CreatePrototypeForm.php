<?php

namespace App\Forms\Division;

use Kris\LaravelFormBuilder\Form;

class CreatePrototypeForm extends Form
{
    public function buildForm()
    {
      $this->add('name','text', ['rules' => 'required']);

      $this->add('caption_weighting_id','entity', [
        'class' => 'App\CaptionWeighting',
        'empty_value' => 'Choose caption weighting...',
        'label' => 'Caption Weighting',
        'expanded' => true,
        'multiple' => false
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
        'help_block' => [
          'text' => 'The Ranked scoring method should be used only if at least one of the following is true: 1) The Caption Weighting is 50/50. 2) All judges are scoring both the Music and Show captions. 3) There are 50% more judges scoring the Music caption than the Show caption.'
        ]
      ]);

      $this->add('sheet_id','entity', [
        'class' => 'App\Sheet',
        'empty_value' => 'Choose scoring sheet...',
        'label' => 'Scoring Sheet'
      ]);
    }
}
