<?php

use Illuminate\Database\Seeder;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            ['name' => 'Canton'],
						['name' => 'Bloomington'],
						['name' => 'Beavercreek']
        ]);
    }
}
