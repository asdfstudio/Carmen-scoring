<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Round;
use Illuminate\Support\Facades\Log;

class RoundScoringCompleted extends Event
{
    use SerializesModels;

    public $round;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
        //Log::debug('RoundScoringCompleted event:'.$this->round->id);

        // Set the target round, if any
        //$this->round = $round->targets()->first();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
