<?php

namespace App\Listeners;

use App\Events\DivisionScoringFinalized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Division;

use Twilio;
use Illuminate\Support\Facades\Log;

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


      $directors = collect();

      // Dvisision > Choirs
      $division->choirs->each(function($choir,$key) use ($directors) {
        foreach($choir->directors as $director)
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

      // Division > Final Round > Choirs
      $finalRound = $division->rounds()->orderBy('sequence', 'DESC')->first();

      if (!$finalRound) return;

      $finalRound->choirs->each(function($choir,$key) use ($directors) {
        foreach($choir->directors as $director)
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

      /*$directors->each(function($director,$key) use ($message) {
        if($director->has('tel'))
        {
          try {
            Twilio::message($director->get('tel'), $message);
          } catch(\Services_Twilio_RestException $e)
          {
            //Log::info($e);
            Log::error('Twilio SMS Error: Failed to deliver message "'.$message.'" to phone number "'.$director->get('tel').'"');
          }
        }
      });*/
    }
}
