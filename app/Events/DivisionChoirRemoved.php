<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Division;
use App\Choir;

class DivisionChoirRemoved extends Event
{
    use SerializesModels;

    public $division;

    public $choir;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Division $division, Choir $choir)
    {
        $this->division = $division;

        $this->choir = $choir;
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
