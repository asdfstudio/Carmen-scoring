<?php

use Illuminate\Database\Seeder;

class ChoirsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('choirs')->insert([
            ['school_id' => 1, 'name' => 'Bulldogs'],
						['school_id' => 2, 'name' => 'Cougars'],
						['school_id' => 3, 'name' => 'Panthers'],
						['school_id' => 4, 'name' => 'Beavers']
        ]);
    }
}
