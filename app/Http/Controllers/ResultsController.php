<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Division;
use App\Round;
use App\Caption;
use App\Judge;
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
      elseif($segment == 'round-shared')
      {
        $current_page = 'round_shared_'.$request->segment(5);
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

      $choirs = $round->choirs;
      $judges = $division->judges;

      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      $show_links = true;

      return view('results.division_round.show', compact('division', 'round', 'scoreboard', 'captions', 'access_code', 'choirs', 'judges', 'show_links'));
    }


    public function divisionRoundShared($division_id, $round_id, $target_round_id, $access_code)
    {
      $this->loadDivision($division_id, $access_code);
      $division = $this->division;
      $captions = $this->captions;

      $round = $division->rounds()->find($round_id)->targets()->find($target_round_id);

      $source_rounds = $round->sources;

      $source_choirs = collect();
      $source_judges = collect();

      $source_rounds->each(function($item, $key) use ($source_choirs, $source_judges) {
        if($item->has('choirs'))
        {
          $item->choirs->each(function($choir,$key) use ($source_choirs) {
            return $source_choirs->push($choir);
          });
        }

        if($item->division->has('judges'))
        {
          $item->division->judges->each(function($judge,$key) use ($source_judges) {
            return $source_judges->push($judge->id);
          });
        }
      });

      $choirs = $source_choirs;
      $judge_ids = $source_judges->unique();
      $judges = Judge::whereIn('id', $judge_ids)->get();

      $scoreboard = new Scoreboard(['round_id' => $source_rounds->pluck('id')->toArray()]);

      //dd($scoreboard->rawScores);

      $show_links = false;

      return view('results.division_round.show_shared', compact('division', 'round', 'scoreboard', 'captions', 'access_code', 'choirs', 'judges', 'show_links'));
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
