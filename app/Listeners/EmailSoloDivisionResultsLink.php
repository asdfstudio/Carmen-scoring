<?php

namespace App\Listeners;

use App\Events\SoloDivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SoloDivision;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;

class EmailSoloDivisionResultsLink
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
      if(env('SEND_SOLO_RESULTS_EMAIL') == false)
      {
        Log::info('EmailSoloDivisionResultsLink listener fired but stopped because of SEND_SOLO_RESULTS_EMAIL ENV variable.');
        return true;
      }

      Log::info('EmailSoloDivisionResultsLink listener fired. Preparing to send email.');
      
      $soloDivision = $event->soloDivision;

      $directors = collect();

      $soloDivision->load('performers', 'performers.choir', 'performers.choir.directors');

      // Dvisision > Performers
      $soloDivision->performers->each(function($performer, $key) use ($directors) {
        foreach($performer->choir->directors as $director)
        {
          if($director->email)
          {
            $directors->push($director);
          }
        }
      });

      // Get unique directors
      $directors = $directors->unique('id');

      Log::debug('Directors: ' . $directors);

      foreach($directors as $director){
        try {
          $email_addresses = [$director->email];
          if($director->emails_additional){
            $emails_additional = array_map('trim', explode(',', $director->emails_additional));
            $email_addresses = array_merge($email_addresses, $emails_additional);
          }
          $this->mailer->send('email.solo_division_finalized',
            ['soloDivision' => $soloDivision],
            function ($m) use ($soloDivision, $email_addresses) {
              $m->to($email_addresses);
              $m->subject($soloDivision->competition->name.", ". $soloDivision->name . " Results Published");
            }
          );
        } catch(\Swift_TransportException $e){
          Log::error('Email Error: Failed to deliver message "'.$soloDivision->competition->name.', '. $soloDivision->name . ' Results Published" to director at "'.implode(', ', $email_addresses).'"');
          report($e);
        }
      }

      // Get organizers
      $organizers = $soloDivision->competition->organization->users()->where('organization_role', 'admin')->get();

      Log::debug('Organizers: ' . $organizers);

      foreach($organizers->pluck('email')->toArray() as $organizer_email){
        try {
          $this->mailer->send('email.solo_division_finalized',
            ['soloDivision' => $soloDivision],
            function ($m) use ($soloDivision, $organizer_email) {
              $m->to($organizer_email);
              $m->subject($soloDivision->competition->name.", ". $soloDivision->name . " Results Published");
            }
          );
        } catch(\Swift_TransportException $e){
          Log::error('Email Error: Failed to deliver message "'.$soloDivision->competition->name.', '. $soloDivision->name . ' Results Published" to organizer at "'.$organizer_email.'"');
          report($e);
        }
      }
    }
}
