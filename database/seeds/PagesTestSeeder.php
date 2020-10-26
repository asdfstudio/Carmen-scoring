<?php

use Illuminate\Database\Seeder;

class PagesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'username' => 'test-page',
            'password' => bcrypt('test-page'),
            'email' => 'test-page@example.org',
            'is_admin' => TRUE
        ]);

        // Seed default scoring system
        $this->call(SheetSeeder::class);
        $this->call(CaptionSeeder::class);
        $this->call(CriterionSeeder::class);
        $this->call(CriterionSheetSeeder::class);
        $this->call(ScoringMethodSeeder::class);
        $this->call(CaptionWeightingSeeder::class);

        // Seed the person types
        $this->call(TypeSeeder::class);

        // Create a basic competition with three divisions
        $competition = factory(\App\Competition::class)->create([
            'name' => 'Test Competition Page'
        ]);

        $fiftyFifty = App\CaptionWeighting::firstWhere('name', '50/50');
        $scoringMethod = App\ScoringMethod::firstWhere('name', 'Ranked Scores');
        $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');

        $roundSettings = ['competition_id' => $competition,
            'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod,
            'sheet_id' => $advancedSheet, 'name' => 'Test Round Page'];

        $Round = factory(App\Round::class)->create($roundSettings);

        $division = factory(App\Division::class)->create(['round_id' => $Round, 'name' => 'Test Division Page']);

        // Add Choirs to the prelim divisions
        for ($i = 1; $i < 10; $i++) {
            $choir = factory(\App\Choir::class)->create([
                'name' => "Choir $i"
            ]);

            if ($i % 2 == 0) {
                $division->choirs()->attach($choir);
            } else {
                $division->choirs()->attach($choir);
            }
        }

        $division->save();

        // Add judges to each division
        $musicJudge = factory(\App\Judge::class)->create([
            'first_name' => 'Music',
            'last_name' => 'Meanie'
        ]);

        $showJudge = factory(\App\Judge::class)->create([
            'first_name' => 'Show',
            'last_name' => 'Nicely'
        ]);

        $allJudge = factory(\App\Judge::class)->create([
            'first_name' => 'All',
            'last_name' => 'Even'
        ]);

        $musicCaption = \App\Caption::firstWhere('name', 'Music');
        $showCaption = \App\Caption::firstWhere('name', 'Show');
        $comboCaption = \App\Caption::firstWhere('name', 'Combo');

        \App\Division::all()->each(function($division) use ($musicJudge, $showJudge, $allJudge, $musicCaption, $showCaption, $comboCaption) {
            $division->round->judges()->attach($musicJudge, ['caption_id' => $musicCaption->id]);
            $division->round->judges()->attach($showJudge, ['caption_id' => $showCaption->id]);
            $division->round->judges()->attach($allJudge, ['caption_id' => $musicCaption->id]);
            $division->round->judges()->attach($allJudge, ['caption_id' => $showCaption->id]);
            $division->round->judges()->attach($allJudge, ['caption_id' => $comboCaption->id]);
        });

    }
}
