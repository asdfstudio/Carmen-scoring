<?php

namespace App\Listeners;

use App\Events\DivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;

use Illuminate\Contracts\Mail\Mailer;

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
        $division = $event->division;

        $directors = collect();

        $division->choirs->each(function($choir,$key) use ($directors) {
          foreach($choir->directors as $director)
          {
            if($director->email)
            {
              $directors->push($director);
            }

          }
        });

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
