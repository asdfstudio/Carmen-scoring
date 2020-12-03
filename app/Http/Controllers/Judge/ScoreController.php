<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;
use App\Competition;
use App\Choir;
use App\Round;
use App\RawScore;
use App\Caption;
use App\Judge;

use App\Carmen\Scorekeeper;

use Auth;

class ScoreController extends Controller
{


		public function save(Request $request)
		{
			$judge_id = Auth::user()->person_id;
			$division_id = $request->input('division_id', NULL);
			$round_id = $request->input('round_id', NULL);
			$choir_id = $request->input('choir_id', NULL);

			$scorekeeper = new Scorekeeper([
				'division_id' => $division_id,
				'round_id' => $round_id,
				'choir_id' => $choir_id,
				'judge_id' => $judge_id
			]);

			$criterion_id = $request->input('criterion_id', NULL);
			$score = $request->input('score', NULL);

			// Save a single score
			if($criterion_id AND $score)
			{
				$response = $scorekeeper->criterion($criterion_id)->score($score)->save();

				if ($response) {
					return response()->json(['success' => true]);
				}
			}
		}
}
