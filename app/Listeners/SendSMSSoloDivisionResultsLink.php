<?php

namespace App\Listeners;

use App\Events\SoloDivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SoloDivision;
use Twilio;
use Illuminate\Support\Facades\Log;

class SendSMSSoloDivisionResultsLink
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
     * @param  SoloDivisionScoringFinalized  $event
     * @return void
     */
    public function handle(SoloDivisionScoringFinalized $event)
    {
      // Skip sending results
      if(env('SEND_SOLO_RESULTS_SMS') == false)
      {
        Log::info('SendSMSSoloDivisionResultsLink listener fired but stopped because of SEND_SOLO_RESULTS_SMS ENV variable.');
        return true;
      }

      $soloDivision = $event->soloDivision;

      $message = "Carmen Scoring: ".$soloDivision->name." results now available at ". route('results.solo-division.show', [$soloDivision, $soloDivision->access_code]);


      $directors = collect();

      $soloDivision->load('performers', 'performers.choir', 'performers.choir.directors');

      // Dvisision > Performers
      $soloDivision->performers->each(function($performer, $key) use ($directors) {
        foreach($performer->choir->directors as $director)
        {
          if($director->getOriginal('tel'))
          {
            $directors->push([
              'id' => $director->id,
              'tel' => $director->getOriginal('tel')
            ]);
          }
        }
      });

      // Get unique directors
      $directors = $directors->unique('id');
      $directors = $directors->unique('tel');

      //dd($directors);

      Log::debug('Directors: '. $directors);

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
