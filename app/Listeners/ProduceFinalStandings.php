<?php

namespace App\Listeners;

use App\Events\RoundScoringCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Round;
use App\Standing;
use App\Carmen\Scoreboard;

class ProduceFinalStandings
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RoundScoringActivated  $event
     * @return void
     */
    public function handle(RoundScoringCompleted $event)
    {
      $round = $event->round;

      if($round == false) return;

      // Check if this is the last round in the division
      $finalRound = $round->division->rounds()->orderBy('sequence', 'DESC')->first();

      //dd($finalRound);

      // Return false if no final round is found
      // or this is not the final round
      if($finalRound == false OR $finalRound->id != $round->id) return;

      // Get scoreboard for the source rounds
      $scoreboard = new Scoreboard(['round_id' => $round->id]);

      //dd($scoreboard->rankedScores);

      // Raw
      if($round->division->scoring_method_id == 1)
      {
        $choirPositions = $scoreboard->rankedScores->total_weighted_rank();
      }
      // Ranked
      else
      {
        $choirPositions = $scoreboard->rankedScores->total_rank();
      }

      $data = [];

      foreach($choirPositions as $position)
      {
        $data[$position['choir_id']] = [
          'raw_rank' => $position['rank'],
          'final_rank' => $position['rank']
        ];
      }

      // Get or create a standing for this division
      $standing = Standing::firstOrCreate(['division_id' => $round->division_id]);
      $standing->round_id = $round->id;
      $standing->choirs()->sync($data);

      $standing->save();

      return;
    }
}
