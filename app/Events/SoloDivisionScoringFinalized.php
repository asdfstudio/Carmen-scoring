<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\SoloDivision;

class SoloDivisionScoringFinalized extends Event
{
    use SerializesModels;

    public $soloDivision;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SoloDivision $soloDivision)
    {
        $this->soloDivision = $soloDivision;
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
