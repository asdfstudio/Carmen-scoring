<?php

use Illuminate\Database\Seeder;

class ChoirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\Choir::class, 20)->make()->each(function ($choir) {
        $choir->school()->associate(App\School::all()->random())->save();
      });
    }
}
