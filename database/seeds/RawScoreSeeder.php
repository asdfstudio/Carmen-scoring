<?php

use Illuminate\Database\Seeder;

class RawScoreSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $competition = App\Competition::firstWhere('name', 'Demo Competition');
    foreach ($competition->divisions as $division) {
      $round = $division->round;
      $judges = $round->judges;
      $choirs = $division->choirs;
      foreach ($choirs as $choir) {
        foreach ($division->sheet->criteria as $criterion) {
          foreach ($judges as $judge) {
            if ($judge->pivot->caption_id == $criterion->caption->id) {
              factory(App\RawScore::class)->create([
                'judge_id' => $judge->id,
                'division_id' => $division->id,
                'round_id' => $round->id,
                'choir_id' => $choir->id,
                'criterion_id' => $criterion->id
              ]);
            }
          }
        }
      }
    }
  }

}
