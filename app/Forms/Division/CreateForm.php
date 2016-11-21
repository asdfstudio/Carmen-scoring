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

				$this->add('scoring_method_id','entity', [
					'class' => 'App\ScoringMethod',
					'empty_value' => 'Choose scoring method...',
					'label' => 'Scoring Method',
          'label_attr' => ['class' => 'block'],
          'expanded' => true,
          'multiple' => false,
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container']
          ],
          'help_block' => [
            'text' => 'The Ranked scoring method should be used only if at least one of the following is true: 1) The Caption Weighting is 50/50. 2) All judges are scoring both the Music and Show captions. 3) There are 50% more judges scoring the Music caption than the Show caption.'
          ]
				]);

				$this->add('sheet_id','entity', [
					'class' => 'App\Sheet',
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
