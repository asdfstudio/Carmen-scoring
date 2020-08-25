<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $competition = App\Competition::firstWhere('name', 'Demo Competition');
    $fiftyFifty = App\CaptionWeighting::firstWhere('name', '50/50');
    $scoringMethod = App\ScoringMethod::first();
    $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');
    $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir');

    $divisions = [
      ['competition_id' => $competition, 'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod, 'sheet_id' => $advancedSheet, 'name' => 'High School - Mens'],
      ['competition_id' => $competition, 'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod, 'sheet_id' => $advancedSheet, 'name' => 'High School - Womens'],
      ['competition_id' => $competition, 'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod, 'sheet_id' => $sheet, 'name' => 'Middle School - Mixed'],
      ['competition_id' => $competition, 'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod, 'sheet_id' => $sheet, 'name' => 'Middle School - Mens'],
    ];

    foreach ($divisions as $division) {
      factory(App\Division::class)->create($division);
    }
  }
}
