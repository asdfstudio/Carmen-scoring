<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Division;
use App\Caption;
use App\Carmen\Scoreboard;

class ResultsController extends Controller
{
    protected $division;
    protected $captions;
    //protected $current_page;

    public function __construct(Request $request)
    {
      $segment = $request->segment(4);

      if($segment == 'standings')
      {
        $current_page = 'standings';
      }
      elseif($segment == 'round')
      {
        $current_page = 'round_'.$request->segment(5);
      }
      else {
        $current_page = 'awards';
      }

      //dd($current_page);

      View::share('current_page', $current_page);
    }

    private function loadDivision($division_id, $access_code)
    {
      $this->division = Division::with(['standings' => function($query) {
        $query->orderBy('caption_id', 'DESC');
      }, 'standings.choirs','awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'awards.choirs' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }])->where('access_code', $access_code)->where('is_published', 1)->find($division_id);

      if($this->division == false)
        abort('404');

      $caption_ids = $this->division->sheet->caption_ids;
      $this->captions = Caption::whereIn('id', $caption_ids)->get();
    }

    public function division($division_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $rounds = $division->rounds->pluck('id')->toArray();

      foreach($rounds as $round_id)
      {
        $scoreboards[$round_id] = new Scoreboard(['round_id' => $round_id]);
      }

      return view('results.division.show', compact('division', 'scoreboards', 'captions', 'access_code'));
    }


    public function divisionStandings($division_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $rounds = $division->rounds->pluck('id')->toArray();

      foreach($rounds as $round_id)
      {
        $scoreboards[$round_id] = new Scoreboard(['round_id' => $round_id]);
      }

      return view('results.division.standings', compact('division', 'scoreboards', 'captions', 'access_code'));
    }

    public function divisionRound($division_id, $round_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $round = $division->rounds()->find($round_id);

      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      return view('results.division_round.show', compact('division', 'round', 'scoreboard', 'captions', 'access_code'));
    }


    public function divisionRoundChoir($division_id, $round_id, $choir_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $round = $division->rounds()->find($round_id);
      $choir = $round->choirs()->find($choir_id);

      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      return view('results.division_round_choir.show', compact('division', 'round', 'choir', 'scoreboard', 'captions', 'access_code'));
    }


    public function divisionRoundJudge($division_id, $round_id, $judge_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $round = $division->rounds()->find($round_id);
      $judge = $division->judges()->with(['captions' => function($query) use ($division_id) {
        $query->where('division_id', $division_id);
      }])->find($judge_id);


      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      return view('results.division_round_judge.show', compact('division', 'round', 'judge', 'scoreboard', 'captions', 'access_code'));
    }
}
