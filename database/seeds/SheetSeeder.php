<?php

use Illuminate\Database\Seeder;

class SheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $names = ['Carmen Showchoir Advanced', 'Carmen Showchoir', 'Carmen Showchoir - Advanced with Accompaniment', 'Carmen Showchoir with Accompaniment'];

      foreach ($names as $name) {
        factory(App\Sheet::class)->create(['name' => $name]);
      }
    }
}
