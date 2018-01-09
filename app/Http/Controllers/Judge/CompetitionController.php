<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;

use Auth;

class CompetitionController extends Controller
{
    public function index()
		{
			$judge_id = Auth::user()->person_id;

			$competitions = Competition::withoutGlobalScope('organization')->whereHas('divisions.judges', function($query) use ($judge_id) {
				$query->where('judge_id',$judge_id);
			})->orWhereHas('soloDivisions.judges', function($query) use ($judge_id) {
				$query->where('judge_id',$judge_id);
			})->active()->get();

			$archivedCompetitions = Competition::withoutGlobalScope('organization')->whereHas('divisions.judges',function($query) use ($judge_id) {
				$query->where('judge_id',$judge_id);
			})->orWhereHas('soloDivisions.judges', function($query) use ($judge_id) {
				$query->where('judge_id',$judge_id);
			})->archived()->get();

			return view('competition.judge.index',compact('competitions','archivedCompetitions'));
		}

		public function show($id)
		{
			$judge_id = Auth::user()->person_id;

			$competition = Competition::withoutGlobalScope('organization')->with(['divisions' => function($query) use ($judge_id) {
					$query->whereHas('judges', function($query) use ($judge_id) {
						$query->where('judge_id', $judge_id);
					});
				}, 'soloDivisions' => function($query) use ($judge_id) {
  					$query->whereHas('judges', function($query) use ($judge_id) {
  						$query->where('judge_id', $judge_id);
  					});
  				},'divisions.judges'])->active()->find($id);

      //dd($competition);

			/*$competition = Competition::with(['divisions','divisions.judges'])->active()->find($id);

			$filtered = $competition->divisions->filter(function($division,$key) {
				return $value->judges
			});*/

			return view('competition.judge.show',compact('competition'));
		}
}
