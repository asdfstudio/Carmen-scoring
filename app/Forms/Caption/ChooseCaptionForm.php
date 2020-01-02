<?php

namespace App\Forms\Caption;

use Kris\LaravelFormBuilder\Form;

class ChooseCaptionForm extends Form
{
    public function buildForm()
    {
        $this->add('caption_id','entity', [
					'class' => 'App\Caption',
					'empty_value' => 'Choose caption ...',
					'label' => 'Caption',
          'label_show' => false,
					'expanded' => true,
					'multiple' => true,
          'choice_options' => [
            'wrapper' => ['class' => 'choice-container'],
            'labelAttrs' => 'label-attr',
          ],
          'selected' => function($data) {
            return $this->model->pluck('id')->toArray();
          },
          'rules' => ['filled']
				]);

				$this->add('submit', 'submit', ['label' => 'Update Captions', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
