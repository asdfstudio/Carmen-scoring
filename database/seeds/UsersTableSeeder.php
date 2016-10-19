<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['email' => 'ab@804studios.com', 'password' => bcrypt('test'), 'person_id' => 1, 'organization_id' => NULL, 'is_admin' => FALSE],
						['email' => 'bc@804studios.com', 'password' => bcrypt('test'), 'person_id' => NULL, 'organization_id' => 1, 'is_admin' => FALSE],
						['email' => 'jkelp@804studios.com', 'password' => bcrypt('test'), 'person_id' => NULL, 'organization_id' => NULL, 'is_admin' => TRUE],
            ['email' => 'carmenshowchoir@gmail.com', 'password' => bcrypt('test'), 'person_id' => NULL, 'organization_id' => NULL, 'is_admin' => TRUE]
        ]);
    }
}
