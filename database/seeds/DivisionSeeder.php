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
    $round1 = App\Round::firstWhere('name', 'Prelims');
    $round2 = App\Round::firstWhere('name', 'Finals');

    $divisions = [
      ['round_id' => $round1, 'name' => 'Demo Band'],
      ['round_id' => $round1, 'name' => 'Demo High School Division 1'],
      ['round_id' => $round1, 'name' => 'Demo High School Division 2'],
      ['round_id' => $round2, 'name' => 'Demo High School Finals'],
    ];

    foreach ($divisions as $division) {
      factory(App\Division::class)->create($division);
    }
  }
}
