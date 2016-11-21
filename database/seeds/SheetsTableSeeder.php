<?php

use Illuminate\Database\Seeder;

class SheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sheets')->insert([
            ['name' => 'Advanced'],
						['name' => 'Novice'],
            ['name' => 'Advanced with Combo'],
            ['name' => 'Novice with Combo'],
            ['name' => 'Combo']
        ]);
    }
}
