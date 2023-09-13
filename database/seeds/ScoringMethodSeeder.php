<?php

use Illuminate\Database\Seeder;

class ScoringMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('scoring_methods')->truncate();
        DB::table('scoring_methods')->insert([
            ["id" => 1,'name' => 'Raw Scores', "created_at" => \Carbon\Carbon::now()],
            ["id" => 2,'name' => 'Ranked Scores', "created_at" => \Carbon\Carbon::now()],
            ["id" => 3,'name' => 'Condorcet - Ranked Pairs Winning', "created_at" => \Carbon\Carbon::now()],
            ["id" => 4,'name' => 'Condorcet - Schultze Winning', "created_at" => \Carbon\Carbon::now()],
            ["id" => 5,'name' => 'Consensus Ordinal Rank', "created_at" => \Carbon\Carbon::now()],
            ["id" => 6,'name' => 'Borda Count', "created_at" => \Carbon\Carbon::now()],
            ["id" => 7,'name' => 'Average Scores', "created_at" => \Carbon\Carbon::now()]
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
