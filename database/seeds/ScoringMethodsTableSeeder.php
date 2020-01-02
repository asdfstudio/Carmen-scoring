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
            ['name' => 'Raw Scores'],
						['name' => 'Ranked Scores'],
						['name' => 'Condorcet - Ranked Pairs Winning'],
						['name' => 'Condorcet - Schultze Winning'],
						['name' => 'Consensus Ordinal Rank'],
						['name' => 'Borda Count']
        ]);
    }
}
