<?php

namespace App\Listeners;

use App\Events\RoundScoringCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Round;
use App\Carmen\Scoreboard;

class SyncRoundChoirsToTarget
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
     * @param  RoundScoringCompleted  $event
     * @return void
     */
    public function handle($event)
    {
        $round = $event->round;

        if($round == false) return;

        // Get the target round
        $targetRound = $round->targets()->first();

        if($targetRound == false) return;

        // How many choirs should be added to this round?
        $max_choirs = $targetRound->max_choirs;

        // Get array of round IDs
        $round_id = $targetRound->sources->pluck('id')->toArray();

        // Get scoreboard for the source rounds
        $scoreboard = new Scoreboard(['round_id' => $round_id]);

        // Raw
        if($targetRound->division->scoring_method_id == 1)
        {
          $choirPositions = $scoreboard->rankedScoresForCurrentMethod->total_weighted_rank();
        }
        // Ranked
        else
        {
          $choirPositions = $scoreboard->rankedScoresForCurrentMethod->total_rank();
        }

        //dd($choirPositions);

        // Filter choirs to only include top x and ties
        $choirs = $choirPositions->reject(function ($item, $choir_id) use ($max_choirs) {
            return $item['rank'] > $max_choirs;
        });

        //dd($choirs);

        // Sync round choirs
        $targetRound->choirs()->sync($choirs->pluck('choir_id')->toArray());
        $round->save();

        return;
    }
}
