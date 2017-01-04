<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Division;

class DivisionScoringFinalized extends Event
{
    use SerializesModels;

    public $division;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Division $division)
    {
        $this->division = $division;
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
