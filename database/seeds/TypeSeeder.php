<?php

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('types')->insert([
        ['id' => 1, 'name' => 'App\Judge'],
        ['id' => 2, 'name' => 'App\Directory'],
        ['id' => 3, 'name' => 'App\Choreographer']
      ]);
    }
}
