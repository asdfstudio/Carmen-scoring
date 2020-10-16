<?php

use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competition = App\Competition::firstWhere('name', 'Demo Competition');
        foreach ($competition->divisions as $division) {
            factory(App\Audience::class)->create([
                'competition_id' => $competition->id,
                'division_id' => $division->id,
                'disable_vote' => 0,
                'limit_result' => 6,
                'is_required_login' => 1,
                'banner_type' => 'hide',
            ]);
        }
    }
}
