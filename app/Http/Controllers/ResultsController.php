<?php

namespace App\Http\Controllers;

use App\Round;
use App\Judge;
use App\Caption;
use App\Director;
use App\Performer;
use App\Division;
use App\Competition;
use App\SoloDivision;
use App\SoloRawScore;
use App\Carmen\Ratings;
use App\Carmen\SoloTotalScores;
use App\Carmen\SoloRankedScores;
use App\Http\Requests;
use App\Carmen\Scoreboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

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
      $this->division = Division::with([
        'competition' => function($query) {
          $query->withoutGlobalScope('organization');
        },
        'standings' => function($query) {
          $query->orderBy('caption_id', 'DESC');
        },
        'standings.choirs',
        'awards' => function($query) {
          $query->withoutGlobalScope('organization');
        },
        'awards.choirs' => function($query) use ($division_id) {
          $query->where('division_id',$division_id);
        },
        'judges' => function($query) {
          $query->groupBy('judge_id');
        },
        'awardSettings'
      ])->where('access_code', $access_code)->where('is_published', 1)->find($division_id);

      if($this->division == false) abort('404');

      $caption_ids = $this->division->sheet->caption_ids;
      $this->captions = Caption::forSheet($this->division->sheet);

      View::share('competition', $this->division->competition);
    }


    public function index()
    {
      $years = [];
      $i = 2017;
      $currentYear = date('Y');

      while($i <= $currentYear)
      {
        $years[] = $i; $i++;
      }

      return view('results.choose_year', compact('years'));
    }


    public function indexYear($year)
    {
      $competitions = Competition::withoutGlobalScope('organization')->completed()->year($year)->orderBy('name', 'asc')->get();

      return view('results.index', compact('competitions', 'year'));
    }

    public function competitionPublic($competition_id, Request $request)
    {
      /*$competition = Competition::with(['divisions' => function($query) {
        $query->published();
      }])->completed()->find($competition_id);*/

      $competition = Competition::withoutGlobalScope('organization')->with(['divisions' => function($query) {
        $query->published();
      }, 'soloDivisions' => function($query) {
        $query->published();
      }])->find($competition_id);

      if (!$competition) {
        return view('results.competition.no-match');
      }

      if($request->session()->has('competition_access_code'))
      {
        return redirect()->route('results.competition.show-custom', [$competition->slug, 'access_code' => $request->session()->get('competition_access_code')]);
      }

      return view('results.competition.show-public', compact('competition'));
    }


    public function competitionCustom($competition_slug, Request $request, FormBuilder $formBuilder)
    {
      $access_code = strtolower($request->input('access_code'));
      $authorized = false;

      $competition = Competition::with(['divisions' => function($query) {
        $query->published();
      }])->where('slug', $competition_slug)->first();

      if($competition == false)
      {
        return redirect()->route('results.index');
      }

      if($access_code AND $competition)
      {
        if($competition->access_code != $access_code)
        {
          $request->session()->forget('competition_access_code');

          return redirect()->route('results.competition.show-custom', [$competition_slug])->with('access_code_alert', 'The access code you entered, "'.$access_code.'", is incorrect.');
        }
        else {
          $authorized = true;
          $request->session()->put('competition_access_code', $access_code);
        }
      }


      $accessCodeForm = $formBuilder->create('Division\AccessCodeForm', [
        'url' => route('results.competition.show-custom', [$competition_slug]),
        'method' => 'post'
      ]);

      return view('results.competition.show-custom', compact('competition', 'accessCodeForm', 'authorized'));
    }


    public function divisionAccessProtected($division_id, Request $request)
    {
      $access_code = $request->input('access_code');

      //dd($access_code);

      $division = Division::with('awardSettings')->where('access_code', $access_code)->where('is_published', 1)->find($division_id);

      // Division not found, check using access code to find director
      if($division == false AND $access_code)
      {
        $division = Division::whereHas('choirs.directors', function($query) use ($access_code) {
          $query->where('email', $access_code);
        })->where('is_published', 1)->find($division_id);

        if($division)
        {
          $access_code = $division->access_code;
        }
      }


      if($division == false)
      {
        return redirect()->route('results.division.show-public', [$division_id])->with('access_code_alert', 'The access code or email address you entered, "'.$access_code.'", is incorrect.');
      }

      return redirect()->route('results.division.show', [$division_id, $access_code]);
    }

    public function divisionPublic($division_id, FormBuilder $formBuilder)
    {
      $division = Division::with(['standings' => function($query) {
        $query->orderBy('caption_id', 'DESC');
      }, 'standings.choirs', 'standings.caption', 'awardSettings',
      'competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },
      'awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'awards.choirs' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }])->where('is_published', 1)->find($division_id);
      
      dd($division);
      $caption_ids = $division->sheet->caption_ids;
      $captions = Caption::forSheet($division->sheet);

      $accessCodeForm = $formBuilder->create('Division\AccessCodeForm', [
        'url' => route('results.division.access-protected', [$division]),
        'method' => 'post'
      ]);

      return view('results.division.show-public', compact('division', 'captions', 'accessCodeForm'));
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

      //$before = memory_get_usage();
      $scoreboard = new Scoreboard(['round_id' => $round_id]);
      $ratings = (new Ratings($round))->all();
      //$after = memory_get_usage();
      //$allocatedSize = ($after - $before);
      //dd($allocatedSize/1024/1024);

      $rawScores = $scoreboard->extendedRawScores;
      $weightedScores = $scoreboard->extendedRawScores;
      switch($division->scoring_method_id){
        case 1:
        case 2:
          // Borda Count
          $rankedScores = $scoreboard->rankedScores;
          break;
        case 3:
          $rankedScores = $scoreboard->condorcetScoresRankedPairs;
          break;
        case 4:
          $rankedScores = $scoreboard->condorcetScoresSchulze;
          break;
        case 5:
          $rankedScores = $scoreboard->consensusOrdinalRankScores;
          break;
        case 6:
          $rankedScores = $scoreboard->bordaCountScores;
          break;
      }
      
      $show_links = true;

      return view('results.division_round.show', compact('division', 'round', 'scoreboard', 'rawScores', 'weightedScores', 'rankedScores', 'captions', 'access_code', 'choirs', 'judges', 'show_links', 'ratings'));
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


      $scoreboard = new Scoreboard(['round_id' => $round_id, 'judge_id' => $judge_id]);

      return view('results.division_round_judge.show', compact('division', 'round', 'judge', 'scoreboard', 'captions', 'access_code'));
    }


    public function soloDivision(FormBuilder $formBuilder, Request $request, SoloDivision $soloDivision, $access_code = NULL)
    {
      $competition = $soloDivision->competition;

      if($request->filled('access_code'))
      {
        return redirect()->route('results.solo-division.show', [$soloDivision, 'access_code' => $request->input('access_code')]);
      }

      if ($soloDivision->access_code != $access_code) {

        $accessCodeForm = $formBuilder->create('SoloDivision\AccessCodeForm', [
          'method' => 'post'
        ]);

        return view('results.solo_division.restricted', compact('access_code', 'accessCodeForm'));
      }

      $category = $request->input('category');

      // Division not found, check using access code to find director
      /*if($division == false AND $access_code)
      {
        $division = Division::whereHas('choirs.directors', function($query) use ($access_code) {
          $query->where('email', $access_code);
        })->where('is_published', 1)->find($division_id);

        if($division)
        {
          $access_code = $division->access_code;
        }
      }*/


      if ($category) {
        $soloDivision->performers = $soloDivision->performers->where('category', $category);

        if ($category == 1) {
          $categoryName = $soloDivision->category_1;
        } elseif ($category == 2) {
          $categoryName = $soloDivision->category_2;
        } else {
          $categoryName = 'Overall';
        }

      } else {
        $categoryName = 'Overall';
      }

      $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)->get();

      $totalScores = (new SoloTotalScores($rawScores , $soloDivision->performers))->get();
      $rankedScores = (new SoloRankedScores($totalScores , $soloDivision->performers))->get();

      if (!$category) {
        $category1Rank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('category', $soloDivision->category_1)))->get();

        $category2Rank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('category', $soloDivision->category_2)))->get();
      } else {
        $category1Rank = null;
        $category2Rank = null;
      }

      $judges = $soloDivision->judges;

      $soloDivision->performers->transform(function($performer, $key) use ($rawScores, $rankedScores, $category1Rank, $category2Rank, $judges, $soloDivision) {
        $performer->rank = $rankedScores->where('performer_id', $performer->id)->pluck('rank')->first();
        $performer->score = $rawScores->where('performer_id', $performer->id)->sum('score');

        if ($performer->category == $soloDivision->category_1) {
          $categoryRank = $category1Rank;
        } elseif ($performer->category == $soloDivision->category_2) {
          $categoryRank = $categoryRank;
        } else {
          $categoryRank = false;
        }

        if ($categoryRank) {
          $performer->category_rank = $categoryRank->where('performer_id', $performer->id)->pluck('rank')->first();
        } else {
          $performer->category_rank = false;
        }

        $judgeScores = [];

        foreach ($judges as $judge) {
          $judgeScores[$judge->id] = $rawScores->where('performer_id', $performer->id)->where('judge_id', $judge->id)->sum('score');
        }

        $performer->judgeScores = $judgeScores;

        return $performer;
      });

      $soloDivision->performers = $soloDivision->performers->sortBy('rank');

      return view('results.solo_division.results', compact('competition', 'judges', 'soloDivision', 'access_code', 'genderName', 'categoryName'));
    }


    public function soloDivisionPerformer(Request $request, FormBuilder $formBuilder, SoloDivision $soloDivision, Performer $performer, $access_code = NULL, $director_email = NULL)
    {
      $competition = $soloDivision->competition;

      $directorValidated = false;

      if ($director_email AND in_array($director_email, $performer->choir->directors->pluck('email')->toArray())) {
        $directorValidated = true;
      }

      if (!$director_email) {
        if($request->filled('director_email') OR $request->session()->has('director_email'))
        {
          if ($request->filled('director_email')) {
            $email = $request->input('director_email');
            $request->session()->put('director_email', $email);
          } elseif ($request->session()->has('director_email')) {
            $email = $request->session()->get('director_email');
          }  else {
            $email = false;
          }

          if ($email) {
            return redirect()->route('results.solo-division.performer.show', [$soloDivision, $performer, $access_code, $email]);
          }
        }
      }


      if (!$directorValidated) {

        $accessCodeForm = $formBuilder->create('SoloDivision\PerformerAccessCodeForm', [
          'method' => 'post',
          'url' => route('results.solo-division.performer.show', [$soloDivision, $performer, $access_code])
        ]);

        return view('results.solo_division.performer-restricted', compact('director_email', 'accessCodeForm'));
      }

      $soloDivision->load('sheet', 'judges');
      $captionsIds = $soloDivision->sheet->caption_ids;
      $captions = Caption::forSheet($soloDivision->sheet);

      $rawScores = SoloRawScore::with(['criterion'])
                    ->where('solo_division_id', $soloDivision->id)
                    ->where('performer_id', $performer->id)
                    ->get();



      return view('results.solo_division.performer-results', compact('competition', 'judges', 'soloDivision', 'access_code', 'performer', 'captions', 'rawScores'));
    }
}
