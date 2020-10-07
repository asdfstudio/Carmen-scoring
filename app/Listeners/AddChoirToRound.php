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

        $choir->rounds()->attach($division->round);
    }
}
