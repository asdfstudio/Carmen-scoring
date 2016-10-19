<?php

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('people')->insert([
            ['first_name' => 'Allison', 'last_name' => 'Bailey', 'email' => 'ab@804studios.com', 'person_type' => 'App\Judge','subject_id' => NULL, 'subject_type' => NULL],
						['first_name' => 'Carl', 'last_name' => 'Day', 'email' => 'cd@804studios.com', 'person_type' => 'App\Choreographer','subject_id' => 1, 'subject_type' => 'App\Choir'],
						['first_name' => 'Emily', 'last_name' => 'Find', 'email' => 'ef@804studios.com', 'person_type' => 'App\Director','subject_id' => 1, 'subject_type' => 'App\Choir'],
						['first_name' => 'Garth', 'last_name' => 'Hill', 'email' => 'gh@804studios.com', 'person_type' => 'App\Judge','subject_id' => NULL, 'subject_type' => NULL],
        ]);
    }
}
