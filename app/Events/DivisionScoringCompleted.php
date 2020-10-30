<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Division;
use Illuminate\Support\Facades\Log;

class DivisionScoringCompleted extends Event
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
        //Log::debug('DivisionScoringCompleted event:'.$this->division->id);
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
