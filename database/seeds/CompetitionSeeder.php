<?php

use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Competition::class)->create([
          'name' => 'Demo Competition',
          'slug' => 'demo-competition',
          'access_code' => 'demo-competition-pass',
          'use_runner_up_names' => TRUE,
          'is_archived' => FALSE,
          'is_completed' => FALSE,
          'begin_date' => strtotime('Friday'),
          'end_date' => strtotime('Sunday'),
          'organization_id' => App\Organization::firstWhere('name', 'Demo Organization')
        ]);

    }
}
