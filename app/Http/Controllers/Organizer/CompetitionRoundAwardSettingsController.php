<?php

namespace App\Http\Controllers\Organizer;

use App\Competition;
use App\Http\Controllers\Controller;
use App\Round;
use App\RoundAwardSetting;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionRoundAwardSettingsController extends Controller
{
    public function edit(Competition $competition, Round $round, FormBuilder $formBuilder)
    {
      $round->load('competition', 'awardSettings', 'sheet', 'sheet.criteria', 'sheet.criteria.caption');
      $round->sheet->captions = $round->sheet->criteria->unique('caption_id')->pluck('caption');

      $form = $formBuilder->create('AwardSetting\RoundAwardSettings', [
        'url' => route('organizer.competition.round.award.settings.store', [$competition, $round]),
        'method' => 'post',
        'data' => [
          'captions' => $round->sheet->captions,
          'awardSettings' => $round->awardSettings
        ]
      ]);

      return view('round_award_settings.organizer.edit', compact('competition', 'round', 'form'));
    }

    public function update(Competition $competition, Round $round, FormBuilder $formBuilder, Request $request)
    {
      $awardSettings = [];

      foreach ($request->input('award_settings') as $index => $awardSetting) {
        $awardSettings[$index] = RoundAwardSetting::firstOrNew([
          'round_id' => $round->id,
          'caption_id' => $awardSetting['caption_id']
        ]);
        $awardSettings[$index]->award_count = $awardSetting['award_count'];
        $awardSettings[$index]->award_sponsors = $awardSetting['award_sponsors'];
      }

      $round->awardSettings()->saveMany($awardSettings);

      return redirect()->route('organizer.competition.round.award.index', [$round->competition, $round])->with('success', 'Caption Awards Updated');
    }
}
