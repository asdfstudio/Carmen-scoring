<?php

use Illuminate\Database\Seeder;

class DivisionJudgeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    App\Round::all()->each(function ($round) {
      $allCaptions = $round->sheet->criteria()->pluck('caption_id')->unique();
      $maxCaptions = $allCaptions->count();
      factory(App\Judge::class, 6)->create()->each(function($judge) use ($round, $allCaptions, $maxCaptions) {
        $captions = $allCaptions->random(rand(1, $maxCaptions));
        foreach ($captions as $caption_id) {
          $round->judges()->attach($judge, ['caption_id' => $caption_id]);
        }
      });
    });

  }
}
