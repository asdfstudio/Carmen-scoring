<?php

namespace App\Listeners;

use App\Events\DivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;

use Illuminate\Contracts\Mail\Mailer;
use Log;

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
        if(env('SEND_FINAL_RESULTS_EMAIL') == false) return true;

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
        $division->rounds()->orderBy('sequence', 'DESC')->first()->choirs->each(function($choir,$key) use ($directors) {
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

        $this->mailer->send('email.division_finalized',
					['division' => $division],
					function ($m) use ($division, $directors) {
            //$m->to('jkelp@804studios.com', 'Jonathan Kelp');
            $m->to($directors->pluck('email')->toArray());
						$m->subject($division->competition->name.", ". $division->name . " Results Published");
        	}
				);
    }
}
