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

        // $this->call(SheetsTableSeeder::class);
				$this->call(CaptionSeeder::class);
				// $this->call(CriteriaTableSeeder::class);
				// $this->call(CriterionSheetTableSeeder::class);
				// $this->call(ScoringMethodsTableSeeder::class);
				// $this->call(CaptionWeightingsTableSeeder::class);
        //
				// $this->call(CompetitionsTableSeeder::class);
				// $this->call(DivisionsTableSeeder::class);
				// $this->call(SchoolsTableSeeder::class);
				// $this->call(ChoirsTableSeeder::class);
				// $this->call(ChoirDivisionTableSeeder::class);
				// $this->call(PlacesTableSeeder::class);
				// $this->call(PeopleTableSeeder::class);
				// $this->call(DivisionJudgeTableSeeder::class);
        // $this->call(DivisionAwardSettingsSeeder::class);
        // $this->call(CommentsTableSeeder::class);
        // $this->call(CommentUrlsTableSeeder::class);
    }
}
