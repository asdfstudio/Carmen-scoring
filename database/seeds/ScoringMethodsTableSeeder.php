<?php

use Illuminate\Database\Seeder;

class ScoringMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scoring_methods')->insert([
            ['name' => 'Raw'],
						['name' => 'Ranked']
        ]);
    }
}
