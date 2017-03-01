<?php

namespace App\Listeners;

use App\Events\DivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;

use Twilio;
use Log;

class SendSMSDivisionResultsLink
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
     * @param  DivisionScoringFinalized  $event
     * @return void
     */
    public function handle(DivisionScoringFinalized $event)
    {
      // Skip sending results
      if(env('SEND_FINAL_RESULTS_SMS') == false)
      {
        Log::info('SendSMSDivisionResultsLink listener fired but stopped because of SEND_FINAL_RESULTS_SMS ENV variable.');
        return true;
      }

      $division = $event->division;

      $message = "Carmen Scoring: ".$division->name." results now available at ". route('results.division.show', [$division, $division->access_code]);

      $division->choirs->each(function($choir,$key) use ($message) {
        foreach($choir->directors as $director)
        {
          if($director->getOriginal('tel'))
          {
            try {
              Twilio::message($director->getOriginal('tel'), $message);
            } catch(\Services_Twilio_RestException $e)
            {
              //Log::info($e);
              Log::error('Twilio SMS Error: Failed to deliver message "'.$message.'" to phone number "'.$director->getOriginal('tel').'"');
            }

          }
        }
      });
    }
}
