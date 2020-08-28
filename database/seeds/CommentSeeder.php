<?php

use Illuminate\Database\Seeder;

use App\Comment;

class CommentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // for each judge in the division, add a comment for each choir
    App\Division::all()->each(function($division) {
      $round = $division->rounds()->first();
      $judges = $division->judges;
      $choirs = $round->choirs;
      foreach ($judges as $judge) {
        foreach ($choirs as $choir) {
          factory(App\Comment::class)->create([
            'judge_id' => $judge->id,
            'choir_id' => $choir->id,
            'recipient_id' => $choir->id,
            'subject_id' => $round->id
          ]);
        }
      }
    });
  }
}
