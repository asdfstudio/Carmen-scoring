<?php

use Illuminate\Database\Seeder;

class ChoirChoreographerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('choir_choreographer')->insert([
            ['choir_id' => 1, 'choreographer_id' => 2]
        ]);
    }
}
