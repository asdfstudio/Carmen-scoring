<?php

namespace App\Http\Controllers\organizer;

use Auth;
use App\Sheet;
use App\Division;
use App\Competition;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\DivisionAwardSetting;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionAwardSettingsController extends Controller
{
    public function edit(Competition $competition, Division $division, FormBuilder $formBuilder)
    {
      $division->load('competition', 'awardSettings', 'sheet', 'sheet.criteria', 'sheet.criteria.caption');
      $division->sheet->captions = $division->sheet->criteria->unique('caption_id')->pluck('caption');

      //dd($division->awardSettings->first()->award_sponsors_array);
      //dd($division->awardSettings->first()->awardSponsor(1));
      //$sponsors = $division->awardSettings->first()->award_sponsors;
      //$sponsors = explode(PHP_EOL, $sponsors);
      //$sponsors = array_combine(range(1, count($sponsors)), $sponsors);
      //dd($sponsors);

      $form = $formBuilder->create('AwardSetting\DivisionAwardSettings', [
        'url' => route('organizer.competition.division.award.settings.store', [$competition, $division]),
        'method' => 'post',
        'data' => [
          'captions' => $division->sheet->captions,
          'awardSettings' => $division->awardSettings
        ]
      ]);

      return view('division_award_settings.organizer.edit', compact('competition', 'division', 'form'));
    }

    public function update(Competition $competition, Division $division, FormBuilder $formBuilder, Request $request)
    {
      //dd($request->input());

      $awardSettings = [];

      foreach ($request->input('award_settings') as $index => $awardSetting) {
        $awardSettings[$index] = DivisionAwardSetting::firstOrNew([
          'division_id' => $division->id,
          'caption_id' => $awardSetting['caption_id']
        ]);
        $awardSettings[$index]->award_count = $awardSetting['award_count'];
        $awardSettings[$index]->award_sponsors = $awardSetting['award_sponsors'];
      }

      //dd($awardSettings);

      $division->awardSettings()->saveMany($awardSettings);

      return redirect()->back();
    }
}
