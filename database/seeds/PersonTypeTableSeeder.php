<?php

use Illuminate\Database\Seeder;

class PersonTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('person_type')->insert([
            ['person_id' => 1, 'type_id' => 1],
            ['person_id' => 2, 'type_id' => 3],
            ['person_id' => 3, 'type_id' => 2],
            ['person_id' => 4, 'type_id' => 1]
        ]);
    }
}
