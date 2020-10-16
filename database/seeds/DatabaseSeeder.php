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
    // Seed a sample org and the admin users
    $this->call(OrganizationSeeder::class);
    $this->call(UserSeeder::class);

    // Seed default scoring system
    $this->call(SheetSeeder::class);
    $this->call(CaptionSeeder::class);
    $this->call(CriterionSeeder::class);
    $this->call(CriterionSheetSeeder::class);
    $this->call(ScoringMethodSeeder::class);
    $this->call(CaptionWeightingSeeder::class);

    // Seed the person types
    $this->call(TypeSeeder::class);

    // Seed a random competition
    $this->call(CompetitionSeeder::class);
    $this->call(RoundSeeder::class);
    $this->call(DivisionSeeder::class);
    $this->call(SchoolSeeder::class);
    $this->call(ChoirSeeder::class);
    $this->call(ChoirDivisionSeeder::class);

    // Seed an audience vote setting
    $this->call(VoteSeeder::class);

    // Add some Judges and random comments and scores
    $this->call(DivisionJudgeSeeder::class);
    $this->call(DivisionAwardSettingsSeeder::class);
    $this->call(CommentSeeder::class);
    // $this->call(CommentUrlSeeder::class);
    $this->call(RawScoreSeeder::class);
  }
}
