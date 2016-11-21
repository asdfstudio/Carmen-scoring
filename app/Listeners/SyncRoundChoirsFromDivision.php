<?php

namespace App\Listeners;

use App\Events\RoundSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Round;

class SyncRoundChoirsFromDivision
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
     * @param  RoundCreated  $event
     * @return void
     */
    public function handle(RoundSaved $event)
    {
      $round = $event->round;

      if($round == false) return false;

      if($round->max_choirs != 0 OR $round->sources->count() != 0) return false;

      $choirs = $round->division->choirs->pluck('id')->toArray();

      $round->choirs()->sync($choirs);
    }
}
