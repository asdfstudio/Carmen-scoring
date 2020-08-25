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
    App\Division::all()->each(function ($division) {
      factory(App\Judge::class, 2)->create()->each(function($judge) use ($division) {
        $division->judges()->attach($judge, ['caption_id' => App\Caption::all()->random()->id]);
      });
    });

  }
}
