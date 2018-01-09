<?php

namespace App\Listeners;

use App\Events\RoundScoringCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Choir;
use App\CommentUrl;
use Illuminate\Contracts\Mail\Mailer;
use Log;

class EmailFeedbackLink
{
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  RoundScoringCompleted  $event
     * @return void
     */
    public function handle(RoundScoringCompleted $event)
    {
        
        // Skip sending results
        if(env('SEND_FEEDBACK_URL_EMAIL') == false)
        {
          Log::info('EmailFeedbackLink listener fired but stopped because of SEND_FEEDBACK_URL_EMAIL ENV variable.');
          return true;
        }

        $round = $event->round;
        $round->load('feedback');
        $competition = $round->division->competition;

        $choirIds = $round->feedback->unique('choir_id')->pluck('choir_id')->toArray();

        if(!$choirIds) return;

        $commentUrls = CommentUrl::with('choir', 'choir.directors')->where('competition_id', $competition->id)->whereIn('choir_id', $choirIds)->get();

        //dd($commentUrls);

        foreach($commentUrls as $commentUrl)
        {
          $directors = collect();

          $commentUrl->choir->directors->each(function($director,$key) use ($directors) {
            if($director->email)
            {
              $directors->push($director);
            }
          });

          // Get unique directors
          $directors = $directors->unique('id');

          Log::debug('Directors to notify of Feedback URL: ' . $directors);

          $this->mailer->send('email.feedback_available',
  					['commentUrl' => $commentUrl, 'competition' => $competition],
  					function ($m) use ($competition, $directors) {
              $m->to($directors->pluck('email')->toArray());
  						$m->subject($competition->name." Feedback Available");
          	}
  				);
        }


    }
}
