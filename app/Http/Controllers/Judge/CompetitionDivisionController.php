<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Caption;
use App\Standing;

use Auth;

class CompetitionDivisionController extends Controller
{

    public function show($competition_id, $division_id)
    {
      $division = Division::with(['choirs','judges' => function($query) {
					$query->distinct('judge_id');
				},'judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				},
				'competition' => function($query) {
          $query->withoutGlobalScope('organization');
        }, 'competition.organization', 'rounds', 'standing','standing.choirs'])->find($division_id);

			$captions = Caption::forSheet($division->sheet);
      $competition = $division->competition;

			return view('competition_division.judge.introduction',compact('division','captions','competition'));
    }


    public function details($competition_id, $division_id)
    {

        $division = Division::with('round')->find($division_id);
        $round_id = $division->round_id;

        $division = $division->load(['choirs', 'round.judges' => function($query) {
            $query->groupBy('judge_id');
        },'round.judges.captions' => function($query) use ($round_id) {
            $query->where('round_id',$round_id);
        },
            'competition' => function($query) {
                $query->withoutGlobalScope('organization');
            },'competition.organization'])->find($division_id);

        $captions = Caption::forSheet($division->sheet);
        $competition = $division->competition;
        $round = $division->round;

        return view('competition_division.judge.show',compact('division', 'round', 'captions','competition'));
    }


    public function scoring($competition_id, $division_id)
    {
        $division = Division::with('round')->find($division_id);
        $round_id = $division->round_id;

        $division = $division->load(['choirs', 'round.judges' => function($query) {
            $query->groupBy('judge_id');
        },'round.judges.captions' => function($query) use ($round_id) {
            $query->where('round_id',$round_id);
        },
            'competition' => function($query) {
                $query->withoutGlobalScope('organization');
            },'competition.organization'])->find($division_id);


        $captions = Caption::forSheet($division->sheet);
        $competition = $division->competition;

        return view('competition_division.judge.scoring', compact('division','captions','competition'));
    }
}
