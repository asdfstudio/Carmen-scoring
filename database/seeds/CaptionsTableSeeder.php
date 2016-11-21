<?php

use Illuminate\Database\Seeder;

class CaptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('captions')->insert([
            ['name' => 'Music'],
						['name' => 'Show'],
            ['name' => 'Combo']
        ]);

    }
}
