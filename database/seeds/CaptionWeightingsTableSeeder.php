<?php

use Illuminate\Database\Seeder;

class CaptionWeightingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('caption_weightings')->insert([
            ['name' => '60/40'],
						['name' => '50/50']
        ]);
    }
}
