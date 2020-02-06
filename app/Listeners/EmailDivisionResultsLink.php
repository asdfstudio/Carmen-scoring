<?php

namespace App\Listeners;

use App\Events\DivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;
use App\Competition;
use App\Organization;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailDivisionResultsLink
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
     * @param  DivisionScoringFinalized  $event
     * @return void
     */
    public function handle(DivisionScoringFinalized $event)
    {
        // Skip sending results
        if(env('SEND_FINAL_RESULTS_EMAIL') == false){
          Log::info('EmailDivisionResultsLink listener fired but stopped because of SEND_FINAL_RESULTS_EMAIL ENV variable.');
          return true;
        }

        Log::info('EmailSoloDivisionFeedbackLink listener fired. Preparing to send email.');
        
        $division = $event->division;

        $directors = collect();

        // Dvisision > Choirs
        $division->choirs->each(function($choir,$key) use ($directors) {
          foreach($choir->directors as $director)
          {
            if($director->email)
            {
              $directors->push($director);
            }
          }
        });

        // Division > Final Round > Choirs
        $finalRound = $division->rounds()->orderBy('sequence', 'DESC')->first();

        if (!$finalRound) return;

        $finalRound->choirs->each(function($choir,$key) use ($directors) {
          foreach($choir->directors as $director)
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
            $this->mailer->send('email.division_finalized',
              ['division' => $division],
              function ($m) use ($division, $email_addresses) {
                $m->to($email_addresses);
                $m->subject($division->competition->name.", ". $division->name . " Results Published");
              }
            );
          } catch(\Swift_TransportException $e){
            Log::error('Email Error: Failed to deliver message "'.$division->competition->name.', '. $division->name . ' Results Published" to director at "'.implode(', ', $email_addresses).'"');
            report($e);
          }
        }
        
        // Get organizers
        $organizers = $division->competition->organization->users()->where('organization_role', 'admin')->get();
        
        Log::debug('Organizers: ' . $organizers);
        
        foreach($organizers->pluck('email')->toArray() as $organizer_email){
          try {
            $this->mailer->send('email.division_finalized',
              ['division' => $division],
              function ($m) use ($division, $organizer_email) {
                $m->to($organizer_email);
                $m->subject($division->competition->name.", ". $division->name . " Results Published");
              }
            );
          } catch(\Swift_TransportException $e){
            Log::error('Email Error: Failed to deliver message "'.$division->competition->name.', '. $division->name . ' Results Published" to organizer at "'.$organizer_email.'"');
            report($e);
          }
        }
    }
}
