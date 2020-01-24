<?php

namespace App\Listeners;

use App\Events\DivisionChoirRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;
use App\Choir;
use App\RawScore;
use Illuminate\Support\Facades\Log;

class RemoveChoirDivisionRawScores
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
     * @param  DivisionChoirRemoved  $event
     * @return void
     */
    public function handle(DivisionChoirRemoved $event)
    {
      $division = $event->division;
      $choir = $event->choir;

      // Stop if we are missing a division or choir
      if(!$division->id OR !$choir->id) return;

      $deletedRows = RawScore::where('division_id', $division->id)->where('choir_id', $choir->id)->delete();

      Log::info('A division choir was removed and the scores for the choir have been deleted. Division: '. $division->id . ', Choir: ' . $choir->id . '. Records deleted: ' . $deletedRows);
    }
}
