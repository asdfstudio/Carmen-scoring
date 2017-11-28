<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Comment;
use App\Competition;

class CommentSaved extends Event
{
    use SerializesModels;

    public $comment;
    public $competition;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, Competition $competition)
    {
        $this->comment = $comment;
        $this->competition = $competition;
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
