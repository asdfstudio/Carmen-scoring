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
			//$judge_id = Auth::user()->person_id;

      //dd($competition);

			$division = Division::with(['choirs','judges' => function($query) {
					//$query->distinct('judge_id');
          $query->groupBy('judge_id');
				},'judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				},
				'competition' => function($query) {
          $query->withoutGlobalScope('organization');
        },'competition.organization', 'rounds'])->find($division_id);

      //$this->authorize('viewFinalStandings', $division);
      //dd($division->judges);

			$captions = Caption::forSheet($division->sheet);
      $competition = $division->competition;

			return view('competition_division.judge.show',compact('division','captions','competition'));
		}


    public function scoring($competition_id, $division_id)
    {
      $division = Division::with(['choirs','judges' => function($query) {
					$query->distinct('judge_id');
				},'judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'competition' => function($query) {
          $query->withoutGlobalScope('organization');
        }, 'competition.organization', 'rounds'])->find($division_id);


			$captions = Caption::forSheet($division->sheet);
      $competition = $division->competition;

			return view('competition_division.judge.scoring', compact('division','captions','competition'));
    }
}
