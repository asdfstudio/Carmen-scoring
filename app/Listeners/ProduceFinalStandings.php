<?php

namespace App\Listeners;

use App\Events\RoundScoringCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Round;
use App\Standing;
use App\Caption;
use App\Carmen\Scoreboard;

use Illuminate\Support\Facades\Log;

class ProduceFinalStandings
{
    protected $round;
    protected $scoreboard;

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
    public function handle($event)
    {
      $this->round = $event->round;

      //Log::debug('ProduceFinalStandings listener started');

      if($this->round == false) return;

      // Check if this is the last round in the division
      $finalRound = $this->round->division->rounds()->orderBy('sequence', 'DESC')->first();

      //Log::debug('This round: '. $this->round->id);
      //Log::debug('Final round: '. $finalRound->id);

      //dd($finalRound);

      // Return false if no final round is found
      // or this is not the final round
      if($finalRound == false OR $finalRound->id != $this->round->id) return;

      // Get scoreboard for the source rounds
      $attr = ['round_id' => $this->round->id];
      $this->scoreboard = new Scoreboard($attr);

      // Overall
      $this->calculateCaptionStandings();

      // Captions -- only those in use by the division's scoring sheet
      $caption_ids = $this->round->division->sheet->caption_ids;

      foreach($caption_ids as $caption_id)
      {
        $this->calculateCaptionStandings($caption_id);
      }

      // Remove standing for captions that aren't available
      $standingsToDelete = Standing::whereNotIn('caption_id', $caption_ids)->whereNotNull('caption_id')->where('round_id', $this->round->id)->get();

      //Log::debug('Standings to delete: '.$standingsToDelete->pluck('id'));

      foreach($standingsToDelete as $toDelete)
      {
        $toDelete->delete();
      }

      return;
    }


    protected function calculateCaptionStandings($caption_id = NULL)
    {
      //Log::debug('calculateCaptionStandings:'.$caption_id);

      // Raw
      if($this->round->division->scoring_method_id == 1)
      {
        $choirPositions = $this->scoreboard->rankedScoresForCurrentMethod->total_weighted_rank($caption_id);
      }
      // Ranked
      else
      {
        $choirPositions = $this->scoreboard->rankedScoresForCurrentMethod->total_rank($caption_id);
      }

      //Log::debug($choirPositions);

      $data = [];

      foreach($choirPositions as $position)
      {
        $data[$position['choir_id']] = [
          'raw_rank' => $position['rank'],
          'final_rank' => $position['rank']
        ];
      }

      // Get or create a standing for this division
      $attr = [
        'division_id' => $this->round->division_id,
        'caption_id' => $caption_id
      ];

      //Log::debug($attr);

      $standing = Standing::firstOrCreate($attr);
      $standing->round_id = $this->round->id;
      $standing->choirs()->sync($data);

      //Log::debug($standing);

      $standing->save();

    }
}
