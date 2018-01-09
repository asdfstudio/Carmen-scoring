<?php

namespace App\Listeners;

use App\Events\SoloDivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailSoloDivisionResultsLink
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
     * @param  SoloDivisionScoringFinalized  $event
     * @return void
     */
    public function handle(SoloDivisionScoringFinalized $event)
    {
        //
    }
}
