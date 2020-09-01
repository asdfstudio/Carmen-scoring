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
        //
        // Create a basic user
        factory(\App\User::class)->create([
            'username' => 'test-admin',
            'password' => bcrypt('test-admin'),
            'email' => 'test-admin@example.org',
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

        // $this->call(CompetitionSeeder::class);
        // $this->call(DivisionSeeder::class);
        // $this->call(SchoolSeeder::class);
        // $this->call(ChoirSeeder::class);
        // $this->call(ChoirDivisionSeeder::class);

        // $this->call(DivisionAwardSettingsSeeder::class);

        // Create a basic competition with three divisions
        $competition = factory(\App\Competition::class)->create([
            'name' => 'Round Change 2020'
        ]);

        $fiftyFifty = App\CaptionWeighting::firstWhere('name', '50/50');
        $scoringMethod = App\ScoringMethod::first();
        $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir');
        $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');

        $divisionSettings = ['competition_id' => $competition, 'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod,
            'sheet_id' => $advancedSheet, 'name' => 'Tough Division'];

        $toughDivision = factory(App\Division::class)->create($divisionSettings);
        $easyDivision = factory(App\Division::class)->create(array_merge($divisionSettings,
            ['sheet_id' => $sheet, 'name' => 'Easy Division']));

        // Add Choirs to the prelim divisions
        foreach(['Two Choir', 'Four Choir', 'Six Choir', 'Eight Choir'] as $choirName) {
            factory(\App\Choir::class)->create([
                'name' => $choirName
            ]);
        }

        App\Division::all()->each(function ($division) {
            $choirs = App\Choir::all();
            $division->choirs()->sync($choirs);
            $division->rounds()->first()->choirs()->sync($choirs);
        });

        // Create a target "Finals Qualifiers" round that is fed by the other divisions
        $finalistsDivision = factory(\App\Division::class)->create(array_merge($divisionSettings,
            ['name' => 'Finalists']));

        //TODO: Round connections here

        // Create a final "Finals" round with the top 6 from all the scored divisions
        $finalsDivision = factory(\App\Division::class)->create(array_merge($divisionSettings,
            ['name' => 'Finals']));

        // Add judges to each division
        $musicJudge = factory(\App\Judge::class)->create([
            'first_name' => 'Music',
            'last_name' => 'Judge'
        ]);

        $showJudge = factory(\App\Judge::class)->create([
            'first_name' => 'Show',
            'last_name' => 'Judge'
        ]);

        $allJudge = factory(\App\Judge::class)->create([
            'first_name' => 'All',
            'last_name' => 'Judge'
        ]);

        $musicCaption = \App\Caption::firstWhere('name', 'Music');
        $showCaption = \App\Caption::firstWhere('name', 'Show');
        $comboCaption = \App\Caption::firstWhere('name', 'Combo');

        \App\Division::all()->each(function($division) use ($musicJudge, $showJudge, $allJudge, $musicCaption, $showCaption, $comboCaption) {
            $division->judges()->attach($musicJudge, ['caption_id' => $musicCaption->id]);
            $division->judges()->attach($showJudge, ['caption_id' => $showCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $musicCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $showCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $comboCaption->id]);
        });
        // TODO: Add Raw Scores for prelims
        // TODO: Create awards in a addition to placing scores

    }
}
