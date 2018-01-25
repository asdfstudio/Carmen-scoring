<?php

use Illuminate\Database\Seeder;
use App\Division;
use App\DivisionAwardSetting;

class DivisionAwardSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all divisions
        $divisions = Division::all(); 

        $captions = [
          '0' => 'overall',
          '1' => 'music',
          '2' => 'show',
          '3' => 'combo'
        ];

        // loop though divisions
        foreach ($divisions as $division) {
          $awardSettings = [];

          $index = 0;

          foreach ($captions as $captionId => $columnName) {

            $awardSettings[$index] = DivisionAwardSetting::firstOrNew([
              'division_id' => $division->id,
              'caption_id' => $captionId
            ]);
            $awardSettings[$index]->award_count = $division->{$columnName.'_award_count'};
            $awardSettings[$index]->award_sponsors = $division->{$columnName.'_award_sponsors'};
            $index++;
          }

          $division->awardSettings()->saveMany($awardSettings);
        }

    }
}
