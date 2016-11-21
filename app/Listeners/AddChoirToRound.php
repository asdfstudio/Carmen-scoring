<?php

namespace App\Listeners;

use App\Events\DivisionChoirCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;
use App\Choir;

class AddChoirToRound
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
     * @param  DivisionChoirCreated  $event
     * @return void
     */
    public function handle(DivisionChoirCreated $event)
    {
        $division = $event->division;
        $choir = $event->choir;

        $rounds = $division->rounds()->where('max_choirs', 0)->get();

        //dd($rounds);

        foreach($rounds as $round)
        {
          if($round->sources->count() == 0)
          {
            $round->choirs()->attach($choir->id);
          }
        }
    }
}
