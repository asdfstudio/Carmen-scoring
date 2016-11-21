<?php

namespace App\Listeners;

use App\Events\DivisionChoirRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;
use App\Choir;

class RemoveChoirFromRound
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

      $rounds = $division->rounds()->where('max_choirs', 0)->get();

      foreach($rounds as $round)
      {
        if($round->sources->count() == 0)
        {
          $round->choirs()->detach($choir->id);
        }
      }
    }
}
