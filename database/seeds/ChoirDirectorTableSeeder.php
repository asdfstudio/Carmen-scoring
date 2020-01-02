<?php

use Illuminate\Database\Seeder;

class ChoirDirectorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('choir_director')->insert([
            ['choir_id' => 1, 'director_id' => 3]
        ]);
    }
}
