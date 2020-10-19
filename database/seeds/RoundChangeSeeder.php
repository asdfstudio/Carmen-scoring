<?php

use Illuminate\Database\Seeder;

use App\Events\RoundSaved;
use App\Events\RoundScoringActivated;
use App\Events\RoundScoringCompleted;


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

        // Create a basic competition with three divisions
        $competition = factory(\App\Competition::class)->create([
            'name' => 'Round Change 2020'
        ]);

        $fiftyFifty = App\CaptionWeighting::firstWhere('name', '50/50');
        $scoringMethod = App\ScoringMethod::firstWhere('name', 'Ranked Scores');
        $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir');
        $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');

        $roundSettings = ['competition_id' => $competition,
            'caption_weighting_id' => $fiftyFifty, 'scoring_method_id' => $scoringMethod,
            'sheet_id' => $advancedSheet, 'name' => 'Round'];

        $Round = factory(App\Round::class)->create($roundSettings);

        $oddDivision = factory(App\Division::class)->create(['round_id' => $Round, 'name' => 'Oddly Easy Division']);
        $evenDivision = factory(App\Division::class)->create(['round_id' => $Round, 'name' => 'Even Tougher Division']);

        // Add Choirs to the prelim divisions
        for ($i = 1; $i < 10; $i++) {
            $choir = factory(\App\Choir::class)->create([
                'name' => "Choir $i"
            ]);

            if ($i % 2 == 0) {
                $evenDivision->choirs()->attach($choir);
            } else {
                $oddDivision->choirs()->attach($choir);
            }
        }

        $oddDivision->save();
        $evenDivision->save();

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
            $division->judges()->attach($musicJudge, ['caption_id' => $musicCaption->id]);
            $division->judges()->attach($showJudge, ['caption_id' => $showCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $musicCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $showCaption->id]);
            $division->judges()->attach($allJudge, ['caption_id' => $comboCaption->id]);
        });

        foreach ($competition->divisions as $division) {
            $round = $division->round;
            $division->activateScoring();
            $judges = $division->judges;
            $choirs = $division->choirs;
            foreach ($choirs as $choir) {
                foreach ($division->sheet->criteria as $criterion) {
                    foreach ($judges as $judge) {
                        if ($judge->pivot->caption_id == $criterion->caption->id) {
                            factory(App\RawScore::class)->create([
                                'score' => $this->getJudgeScore($judge->last_name, $division->name, $choir->name),
                                'judge_id' => $judge->id,
                                'division_id' => $division->id,
                                'round_id' => $round->id,
                                'choir_id' => $choir->id,
                                'criterion_id' => $criterion->id
                            ]);
                        }
                    }
                }
            }
            $division->completeScoring();
        }

    }


    /**
     * Helper function so we can get different grades for the different judges for testing later
     *
     */
    private function getJudgeScore($judgeName, $divisionName, $choirName)
    {
        $score = (int) substr($choirName, -1);

        switch ($divisionName) {
        case "Even Tougher Division":
            $score -= 1;
            break;
        case "Oddly Easy Division":
            $score += 1;
            break;
        }

        switch ($judgeName) {
        case "Meanie":
            $score -= 1;
            break;
        case "Nicely":
            $score += 1;
            break;
        }

        return $score;

    }
}
