<?php

namespace App\Listeners;

use App\Events\SoloDivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Choir;
use App\CommentUrl;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;

class EmailSoloDivisionFeedbackLink
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
     * @param  SoloDivisionScoringFinalized  $event
     * @return void
     */
    public function handle(SoloDivisionScoringFinalized $event)
    {
      // Skip sending results
      if(env('SEND_FEEDBACK_URL_EMAIL') == false)
      {
        Log::info('EmailSoloDivisionFeedbackLink listener fired but stopped because of SEND_FEEDBACK_URL_EMAIL ENV variable.');
        return true;
      }
      
      Log::info('EmailSoloDivisionFeedbackLink listener fired. Preparing to send email.');

      $soloDivision = $event->soloDivision;
      $soloDivision->load('competition', 'performers', 'performers.choir');
      $competition = $soloDivision->competition;

      $choirIds = $soloDivision->performers->unique('choir_id')->pluck('choir_id')->toArray();

      if(!$choirIds) return;

      $commentUrls = CommentUrl::with('choir', 'choir.directors')->where('competition_id', $competition->id)->whereIn('choir_id', $choirIds)->get();

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

        if ($directors->count() < 1) return;

        foreach($directors as $director){
          try {
            $email_addresses = [$director->email];
            if($director->emails_additional){
              $emails_additional = array_map('trim', explode(',', $director->emails_additional));
              $email_addresses = array_merge($email_addresses, $emails_additional);
            }
            $this->mailer->send('email.feedback_available',
            ['commentUrl' => $commentUrl, 'competition' => $competition],
              function ($m) use ($competition, $email_addresses) {
                $m->to($email_addresses);
                $m->subject($competition->name." Feedback Available");
              }
            );
          } catch(\Swift_TransportException $e){
            Log::error('Email Error: Failed to deliver message "'.$competition->name.' Feedback Available" to director at "'.implode(', ', $email_addresses).'"');
            report($e);
          }
        }
      }
    }
}
