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

      $this->mailer->send('email.solo_division_finalized',
        ['soloDivision' => $soloDivision],
        function ($m) use ($soloDivision, $directors) {
          $m->to($directors->pluck('email')->toArray());
          $m->subject($soloDivision->competition->name.", ". $soloDivision->name . " Results Published");
        }
      );
    }
}
