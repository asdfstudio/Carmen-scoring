<?php

use Illuminate\Database\Seeder;

class ChoirDivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('choir_division')->insert([
            ['division_id' => 1, 'choir_id' => 1],
						['division_id' => 1, 'choir_id' => 2],
						['division_id' => 1, 'choir_id' => 3],
						['division_id' => 2, 'choir_id' => 1],
						['division_id' => 2, 'choir_id' => 2],
						['division_id' => 3, 'choir_id' => 2],
						['division_id' => 3, 'choir_id' => 3],
        ]);
    }
}
