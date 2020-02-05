<?php

namespace App\Listeners;

use App\Events\RoundScoringCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Choir;
use App\CommentUrl;
use App\Division;

use Twilio;
use Illuminate\Support\Facades\Log;

class SendSMSFeedbackLink
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
     * @param  RoundScoringCompleted  $event
     * @return void
     */
    public function handle(RoundScoringCompleted $event)
    {
      // Skip sending results
      if(env('SEND_FEEDBACK_URL_SMS') == false)
      {
        Log::info('SendSMSFeedbackLink listener fired but stopped because of SEND_FEEDBACK_URL_SMS ENV variable.');
        return true;
      }

      $round = $event->round;
      $round->load('feedback');
      $competition = $round->division->competition;

      $choirIds = $round->feedback->unique('choir_id')->pluck('choir_id')->toArray();

      if(!$choirIds) return;

      $commentUrls = CommentUrl::with('choir', 'choir.directors')
                                ->where('competition_id', $competition->id)
                                ->whereIn('choir_id', $choirIds)->get();


      foreach($commentUrls as $commentUrl)
      {
        $directors = collect();

        $commentUrl->choir->directors->each(function($director,$key) use ($directors) {
          if($director->getOriginal('tel'))
          {
            $directors->push([
              'id' => $director->id,
              'tel' => $director->getOriginal('tel')
            ]);
          }
        });

        // Get unique directors
        $directors = $directors->unique('id');

        //dd($directors);

        $message = "Carmen Scoring: ".$competition->name." feedback now available at ". route('feedback.show', [$commentUrl->access_code]);

        Log::debug('Directors to notify via SMS of Feedback URL: ' . $directors);
        Log::debug('Message to Directors: ' . $message);

        if ($directors->count() < 1) return;

        foreach($directors as $director)
        {
          try {
            Twilio::message($director['tel'], $message);
          } catch(\Services_Twilio_RestException $e)
          {
            Log::error('Twilio SMS Error: Failed to deliver message "'.$message.'" to phone number "'.$director['tel'].'"');
            report($e);
          }
        }
      }
    }
}
