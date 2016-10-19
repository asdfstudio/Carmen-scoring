<?php

use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('places')->insert([
            ['city' => 'Canton','state' => 'OH', 'subject_id' => 1, 'subject_type' => 'App\School'],
						['city' => 'Canton','state' => 'OH', 'subject_id' => 1, 'subject_type' => 'App\Competition'],
						['city' => 'Canton','state' => 'OH', 'subject_id' => 1, 'subject_type' => 'App\Organization'],
        ]);
    }
}
