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
    // loop though divisions
    foreach (Division::all() as $division) {
      // Don't give more awards than we have choirs
      $max = $division->choirs()->count();

      // Add the Overall Caption w/ ID = 0
      factory(App\DivisionAwardSetting::class)->create([
        'division_id' => $division->id,
        'caption_id' => 0,
        'award_count' => rand(0, $max-1)
      ]);

      foreach ($division->sheet->criteria()->pluck('caption_id')
        ->unique()->values() as $captionId) {

        factory(App\DivisionAwardSetting::class)->create([
          'division_id' => $division->id,
          'caption_id' => $captionId,
          'award_count' => rand(0, $max-1)
        ]);

      }
    }
  }
}
