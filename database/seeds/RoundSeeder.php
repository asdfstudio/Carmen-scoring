<?php

use Illuminate\Database\Seeder;

class RoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competition = App\Competition::firstWhere('name', 'Demo Competition');
        $fiftyFifty = App\CaptionWeighting::firstWhere('name', '50/50');
        $scoringMethod = App\ScoringMethod::first();
        $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');

        factory(App\Round::class)->create([
            'name' => 'Prelims',
            'competition_id' => $competition,
            'caption_weighting_id' => $fiftyFifty,
            'scoring_method_id' => $scoringMethod,
            'sheet_id' => $advancedSheet
        ]);

        $sixtyFourty = App\CaptionWeighting::firstWhere('name', '60/40');
        $scoringMethod = App\ScoringMethod::firstWhere('name', 'Consensus Ordinal Rank');
        $advancedSheet = App\Sheet::firstWhere('name', 'Carmen Showchoir');

        factory(App\Round::class)->create([
            'name' => 'Finals',
            'competition_id' => $competition,
            'caption_weighting_id' => $sixtyFourty,
            'scoring_method_id' => $scoringMethod,
            'sheet_id' => $advancedSheet
        ]);
    }
}
