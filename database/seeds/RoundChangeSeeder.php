<?php

use Illuminate\Database\Seeder;

class RoundChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model: Loveland 2020 Showfest in prod - #71
        // Create five divisions
        // Add a round
        // Create a target "Finals Qualifiers" round
        // Create a final "Finals" round with the top 6 from all the scored divisions
        // Create awards in a addition to placing scores:b
        //
        // Create a basic user
        factory(\App\User::class)->create([
            'username' => 'test-admin',
            'email' => 'test-admin@example.org',
            'is_admin' => TRUE
        ]);

        // Create a competition
        $competition = factory(\App\Competition::class)->create();

        $prelims = factory(\App\Division::class, 6)->create()->map(function($division) {
            return $division->rounds->first();
        });

        // Create a target "Finals Qualifiers" round that is fed by the other divisions
        $finalistsDivision = factory(\App\Division::class)->create([
            'name' => 'Finalists'
        ]);
        $finalistsDivision->rounds->first()->sources()->sync($prelims);

        // Create a final "Finals" round with the top 6 from all the scored divisions
        $finalsDivision = factory(\App\Division::class)->create([
            'name' => 'Finals'
        ]);

        // Add Choirs to the prelims
        // Add Judges to the prelims and add their scores
    }
}
