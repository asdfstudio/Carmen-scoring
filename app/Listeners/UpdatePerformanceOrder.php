<?php

namespace App\Listeners;

use App\Events\PerformanceOrderChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePerformanceOrder
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
     * @param  PerformanceOrderChanged  $event
     * @return void
     */
    public function handle(PerformanceOrderChanged $event)
    {
        //
    }
}
