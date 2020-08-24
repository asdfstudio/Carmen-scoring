<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed people and organizations
				$this->call(OrganizationSeeder::class);
				$this->call(UserSeeder::class);

        $this->call(SheetSeeder::class);
				$this->call(CaptionSeeder::class);
				$this->call(CriterionSeeder::class);
				$this->call(CriterionSheetSeeder::class);
				// $this->call(ScoringMethodSeeder::class);
				$this->call(CaptionWeightingSeeder::class);
        //
				// $this->call(CompetitionSeeder::class);
				// $this->call(DivisionSeeder::class);
				// $this->call(SchoolSeeder::class);
				// $this->call(ChoirSeeder::class);
				// $this->call(ChoirDivisionSeeder::class);
				// $this->call(PlaceSeeder::class);
				// $this->call(PeopleSeeder::class);
				// $this->call(DivisionJudgeSeeder::class);
        // $this->call(DivisionAwardSettingsSeeder::class);
        // $this->call(CommentSeeder::class);
        // $this->call(CommentUrlSeeder::class);
    }
}
