<?php

use Illuminate\Database\Seeder;

class CompetitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('competitions')->insert([
            ['organization_id' => 1, 'name' => 'Canton 2016'],
						['organization_id' => 1, 'name' => 'Canton 2017'],
						['organization_id' => 2, 'name' => 'Bloomington 2017'],
						['organization_id' => 3, 'name' => 'Beavercreek 2016']
        ]);
    }
}
