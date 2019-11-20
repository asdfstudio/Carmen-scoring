<?php

namespace App\Forms\AwardSetting;

use Kris\LaravelFormBuilder\Form;

class AwardSetting extends Form
{
    public function buildForm()
    {
      //dd($this->data);
      $this->add('caption_'.rand(1111,9999), 'static', [
        'label_show' => false,
        'tag' => 'h2',
        'value' => $this->getData('heading')
      ]);

      $this->add('caption_id', 'hidden', [
        'default_value' => $this->getData('caption_id')
      ]);;

      if ($this->data['model']) {
        $awardCount = $this->data['model']->award_count;
        $awardSponsors = $this->data['model']->award_sponsors;
      } else {
        $awardCount = 0;
        $awardSponsors = false;
      }

      $this->add('award_count','number', [
        'rules' => 'required',
        'help_block' => [
          'text' => 'How many choirs will receive awards?'
        ],
        'default_value' => $awardCount,
        'attr' => ['min' => 0]
      ]);

      $this->add('award_sponsors','textarea', [
        'default_value' => $awardSponsors,
        'help_block' => [
          'text' => 'Enter 1 sponsor per line, with Grand Champion sponsor on line 1, 1st runner up on line 2 and so on...'
        ]
      ]);

    }
}
