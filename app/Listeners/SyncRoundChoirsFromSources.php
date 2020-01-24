<?php

namespace App\Listeners;

use App\Events\RoundScoringActivated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Round;
use App\Carmen\Scoreboard;

class SyncRoundChoirsFromSources
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
    public function handle(RoundScoringActivated $event)
    {
        $round = $event->round;

        if($round == false) return false;

        // No sources, use all division choirs
        if($round->sources->isEmpty())
        {
          $choirs = $round->division->choirs->pluck('id')->toArray();
          return $round->choirs()->sync($choirs);
        }
        else
        {
          // How many choirs should be added to this round?
          $max_choirs = $round->max_choirs;

          // Get array of round IDs
          $round_id = $round->sources->pluck('id')->toArray();

          // Get scoreboard for the source rounds
          $scoreboard = new Scoreboard(['round_id' => $round_id]);

          // Raw
          if($round->division->scoring_method_id == 1)
          {
            $choirPositions = $scoreboard->rankedScoresForCurrentMethod->total_weighted_rank();
          }
          // Ranked
          else
          {
            $choirPositions = $scoreboard->rankedScoresForCurrentMethod->total_rank();

          }

          // Filter choirs to only include top x and ties
          $choirs = $choirPositions->reject(function ($item, $choir_id) use ($max_choirs) {
              return $item['rank'] > $max_choirs;
          });

          // Sync round choirs
          $round->choirs()->sync($choirs->pluck('choir_id')->toArray());
          return $round->save();
        }
    }
}
