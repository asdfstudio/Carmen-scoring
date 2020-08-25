<?php

use Illuminate\Database\Seeder;

class ChoirDivisionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Take each Division
    App\Division::all()->each(function ($division) {
      // Seed it with four random choirs
      $choirs = App\Choir::all()->random(4);
      $division->choirs()->sync($choirs);
    });
  }
}
