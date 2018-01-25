<?php

namespace App\Forms\AwardSetting;

use Kris\LaravelFormBuilder\Form;

class DivisionAwardSettings extends Form
{
    public function buildForm()
    {
      //dd($this->data['awardSettings']->where('caption_id', 0)->first());
      $this->add('award_settings[0]', 'form', [
        'class' => 'AwardSetting\AwardSetting',
        'label_show' => false,
        'data' => [
          'heading' => 'Overall',
          'caption_id' => 0,
          'model' => $this->data['awardSettings']->where('caption_id', 0)->first()
        ]
      ]);


      if ($this->data['captions']) {
        foreach ($this->data['captions'] as $caption) {

          $this->add('award_settings['.$caption->id.']', 'form', [
            'class' => 'AwardSetting\AwardSetting',
            'label_show' => false,
            'data' => [
              'heading' => $caption->name,
              'caption_id' => $caption->id,
              'model' => $this->data['awardSettings']->where('caption_id', $caption->id)->first()
            ]
          ]);

        }
      }


			$this->add('submit', 'submit', [
        'label' => 'Save Award Settings',
        'value' => 'submit',
        'attr' => ['class' => 'btn btn-primary', 'name' => 'submit']
      ]);

    }
}
