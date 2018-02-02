<?php

namespace App\Listeners;

use App\Events\CommentSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\CommentUrl;

class CreateCommentsUrlIfNonexistent
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
     * @param  CommentSaved  $event
     * @return void
     */
    public function handle(CommentSaved $event)
    {
        $comment = $event->comment;
        $competition = $event->competition;

        if ($comment->recipient_type == 'App\Choir') {
          $choir = $comment->recipient;
        } elseif ($comment->recipient_type == 'App\Performer') {
          $choir = $comment->recipient->choir;
        } else {
          $choir = false;
        }

        if (!$choir) return;

        $commentUrl = CommentUrl::firstOrCreate([
          'competition_id' => $competition->id,
          'recipient_type' => 'App\Choir',
          'recipient_id' => $choir->id,
          'choir_id' => $choir->id
        ]);

        if(!$commentUrl->wasRecentlyCreated) return;

        $commentUrl->access_code = str_random(8);
        return $commentUrl->save();
    }
}
