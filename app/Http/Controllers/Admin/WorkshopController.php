<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Round;

use Event;
use App\Events\DivisionScoringFinalized;
use App\Events\RoundScoringActivated;
use App\Events\RoundScoringCompleted;
use App\Events\RoundSaved;

class WorkshopController extends Controller
{
    public function __construct()
    {
        if(env('IS_WORKSHOP_ENABLED') === false)
        {
          echo 'Workshop Mode is currently not enabled.';
          exit;
        }
        $this->competitions = Competition::all();
        $this->divisions = Division::all();
        $this->rounds = Round::all();
    }

    public function index()
    {
      return view('workshop.index');
    }

    public function open()
    {
      echo 'Start to open..';
      $this->activateCompetitions();
      $this->activateDivisionScoring();
      $this->activateRoundScoring();
      echo '..done opening.';
      return redirect()->route('workshop.index')->with('success', 'Scoring opened.');
    }

    public function close()
    {
      echo 'Start to close..';
      $this->completeRoundScoring();
      $this->completeDivisionScoring();
      echo '..done closing.';
      return redirect()->route('workshop.index')->with('success', 'Scoring closed.');
    }

    public function finalize()
    {
      echo 'Start to finalize..';
      $this->finalizeDivisionScoring();
      $this->completeCompetitions();
      echo '..done finalizing.';
      return redirect()->route('workshop.index')->with('success', 'Scoring finalized.');
    }

    //
    //

    public function activateCompetitions()
    {
      foreach($this->competitions as $competition)
      {
        $competition->activate();
      }

    }

    public function completeCompetitions()
    {
      foreach($this->competitions as $competition)
      {
        $competition->complete();
      }

    }

    public function activateDivisionScoring()
    {
      foreach($this->divisions as $division)
      {
        $division->activateScoring();
      }
    }

    public function completeDivisionScoring()
    {
      foreach($this->divisions as $division)
      {
        $division->completeScoring();
      }
    }

    public function finalizeDivisionScoring()
    {
      foreach($this->divisions as $division)
      {
        $division->finalizeScoring();
        Event::fire(new DivisionScoringFinalized($division));
      }

    }

    public function activateRoundScoring()
    {
      foreach($this->rounds as $round)
      {
        $round->activateScoring();
        Event::fire(new RoundScoringActivated($round));
      }
    }

    public function deactivateRoundScoring()
    {
      foreach($this->rounds as $round)
      {
        $round->deactivateScoring();
      }
    }

    public function completeRoundScoring()
    {
      foreach($this->rounds as $round)
      {
        $round->completeScoring();
        Event::fire(new RoundScoringCompleted($round));
      }
    }
}
