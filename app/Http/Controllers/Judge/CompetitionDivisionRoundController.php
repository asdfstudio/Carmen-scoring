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
use App\Comment;
use App\Recording;
use App\Carmen\Scorekeeper;
use App\Carmen\Scoreboard;
use App\Events\CommentSaved;

use DB;
use Auth;

class CompetitionDivisionRoundController extends Controller
{


    public function summary($competition_id, $division_id, $round_id)
    {

        $judge_id = Auth::user()->person_id;

        $rawScores = RawScore::with('judge','choir')
            ->where('division_id',$division_id)
            ->where('round_id',$round_id)
            ->where('judge_id',$judge_id)
            ->get();

        $scoreboard = new Scoreboard(['division_id' => $division_id, 'round_id' => $round_id]);

        $rawScores = $scoreboard->rawScores;
        $weightedScores = $scoreboard->weightedScores;

        $division = Division::with('round')->find($division_id);
        $round_id = $division->round_id;

        $division = $division->load(['choirs', 'choirs.recordings', 'round.judges' => function($query) {
            $query->groupBy('judge_id');
        },'round.judges.captions' => function($query) use ($round_id) {
            $query->where('round_id',$round_id);
        }, 'round.judges.captions.criteria',
            'competition' => function($query) {
                $query->withoutGlobalScope('organization');
            },'competition.organization'])->find($division_id);

        $captions = Caption::forSheet($division->sheet);
        $competition = $division->competition;
        $round = $division->round;
        // $round = Round::with(['divisions','division.competition' => function($query) {
        //   $query->withoutGlobalScope('organization');
        // },'round.judges' => function($query) use ($judge_id) {
        //     $query->where('judge_id',$judge_id)->first();
        //   }, 'round.judges.captions' => function($query) use ($division_id) {
        //     $query->where('division_id',$division_id);
        //   },
        //    'round.judges.captions.criteria','choirs',
        //    'choirs.recordings' => function($query) use ($division_id, $judge_id) {
        //     $query->where('division_id', $division_id)->where('judge_id', $judge_id);
        //   }])->find($round_id);


        $captions = Caption::forSheet($division->sheet);
        return view('competition_round.judge.summary',compact('rawScores', 'weightedScores', 'captions', 'round', 'competition', 'division'));
    }

    public function spreadsheet($competition_id, $round_id)
    {
        $judge_id = Auth::user()->person_id;

        $round = Round::with(['competition', 'divisions',
            'judges' => function($query) use ($judge_id) {
                $query->where('id', $judge_id);
            }])->find($round_id);

        if ($round->divisions->count() == 0) {
            return redirect()->route('judge.competition.show', [$competition])->with('warning', 'No choirs have been designated for this round yet.');
        }

        $apiUrl = route('api.spreadsheet.show', [$round_id]);
        $backUrl = route('judge.competition.show', [$competition_id]);

        return view('judge.spreadsheet', compact('apiUrl', 'backUrl'));
    }

    public function details($competition,$division_id,$round_id)
    {
        $judge_id = Auth::user()->person_id;

        $rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

        $round = Round::with(['division','division.competition' => function($query) {
            $query->withoutGlobalScope('organization');
        },'division.judges' => function($query) use ($judge_id) {
            $query->where('judge_id',$judge_id)->first();
        }, 'division.judges.captions' => function($query) use ($division_id) {
            $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria'])->find($round_id);


        $captions = Caption::forSheet($round->division->sheet);

        return view('competition_division_round.judge.show',compact('rawScores','captions','round', 'captions'));
    }

    public function save_scores($competition_id, $division_id, $round_id, Request $request)
    {
        $judge_id = Auth::user()->person_id;

        $scorekeeper = new Scorekeeper([
            'division_id' => $division_id,
            'round_id' => $round_id,
            'judge_id' => $judge_id
        ]);

        $choirScores = $request->input('scores', NULL);

        // Save multiple scores
        if($choirScores)
        {
            $response = $scorekeeper->save_multiple_choirs_scores($choirScores);

            return $response;
        }
    }
}
