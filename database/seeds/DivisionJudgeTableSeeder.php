<?php

use Illuminate\Database\Seeder;

class DivisionJudgeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('division_judge')->insert([
            ['division_id' => 1, 'judge_id' => 1, 'caption_id' => 1],
						['division_id' => 1, 'judge_id' => 1, 'caption_id' => 2],
						['division_id' => 1, 'judge_id' => 4, 'caption_id' => 1],
						['division_id' => 2, 'judge_id' => 1, 'caption_id' => 1],
						['division_id' => 2, 'judge_id' => 1, 'caption_id' => 2],
						['division_id' => 2, 'judge_id' => 4, 'caption_id' => 1],
						['division_id' => 3, 'judge_id' => 1, 'caption_id' => 1],
						['division_id' => 3, 'judge_id' => 1, 'caption_id' => 2],
						['division_id' => 3, 'judge_id' => 4, 'caption_id' => 1],
						['division_id' => 3, 'judge_id' => 4, 'caption_id' => 2]
        ]);
    }
}
