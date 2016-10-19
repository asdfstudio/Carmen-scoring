<?php

use Illuminate\Database\Seeder;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert([
            ['competition_id' => 1, 'caption_weighting_id' => 1, 'scoring_method_id' => 1, 'sheet_id' => 1, 'name' => 'High School - Mens'],
						['competition_id' => 1, 'caption_weighting_id' => 1, 'scoring_method_id' => 1, 'sheet_id' => 1, 'name' => 'High School - Womens'],
						['competition_id' => 2, 'caption_weighting_id' => 2, 'scoring_method_id' => 2, 'sheet_id' => 2, 'name' => 'High School - Mens'],
						['competition_id' => 3, 'caption_weighting_id' => 1, 'scoring_method_id' => 1, 'sheet_id' => 2, 'name' => 'High School - Mens'],
						['competition_id' => 3, 'caption_weighting_id' => 1, 'scoring_method_id' => 2, 'sheet_id' => 1, 'name' => 'Middle School - Mixed'],
						['competition_id' => 3, 'caption_weighting_id' => 1, 'scoring_method_id' => 2, 'sheet_id' => 2, 'name' => 'Middle School - Mens'],
        ]);
    }
}
