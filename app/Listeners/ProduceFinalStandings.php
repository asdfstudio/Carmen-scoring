<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;
use App\Standing;
use App\Caption;
use App\Carmen\Scoreboard;

use Illuminate\Support\Facades\Log;

class ProduceFinalStandings
{
    protected $division;
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
     * @param  Event $event
     * @return void
     */
    public function handle($event)
    {
      $this->division = $event->division;

      if($this->division == false) return;

      $this->scoreboard = new Scoreboard(['division_id' => $this->division->id]);

      // Overall
      $this->calculateCaptionStandings();

      // Captions -- only those in use by the division's scoring sheet
      $caption_ids = $this->division->round->sheet->caption_ids;

      foreach($caption_ids as $caption_id)
      {
        $this->calculateCaptionStandings($caption_id);
      }

      // Remove standing for captions that aren't available
      Standing::whereNotIn('caption_id', $caption_ids)->whereNotNull('caption_id')->where('division_id', $this->division->id)->delete();

      return;
    }

    protected function calculateCaptionStandings($caption_id = NULL)
    {

      // Raw
      if($this->division->round->scoring_method_id == 1)
      {
        $choirPositions = $this->scoreboard->rankedScoresForCurrentMethod->total_weighted_rank($caption_id);
      }
      // Ranked
      else
      {
        $choirPositions = $this->scoreboard->rankedScoresForCurrentMethod->total_rank($caption_id);
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
      $attr = [
        'division_id' => $this->division->id,
        'caption_id' => $caption_id
      ];

      $standing = Standing::firstOrCreate($attr);
      $standing->round_id = $this->division->round->id;
      $standing->choirs()->sync($data);

      $standing->save();

    }
}
