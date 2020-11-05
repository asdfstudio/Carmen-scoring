<?php

namespace App\Events;

use App\Round;
use App\Choir;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerformanceOrderChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $round;
    public $choir;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Round $round, Choir $choir = null)
    {
        $this->round = $round;
        $this->choir = $choir;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
