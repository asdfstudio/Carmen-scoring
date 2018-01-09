<?php

namespace App\Http\Controllers\Judge;

use Auth;
use App\Caption;
use App\Performer;
use App\Competition;
use App\SoloDivision;
use App\SoloRawScore;
use App\Http\Requests;
use App\Carmen\SoloScorekeeper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class SoloDivisionPerformerController extends Controller
{
    public function edit(Competition $competition, SoloDivision $soloDivision, Performer $performer, FormBuilder $formBuilder)
    {
      $form = $formBuilder->create('Performer\EditForm', [
        'url' => route('judge.competition.solo-division.performer.update', [$competition, $soloDivision, $performer]),
        'model' => $performer
      ]);

      return view('performer.judge.edit', compact('competition', 'soloDivision', 'performer', 'form'));
    }

    public function update(Competition $competition, SoloDivision $soloDivision, Performer $performer, Request $request)
    {
      $performer->name = $request->input('name');
      $performer->gender = $request->input('gender');
      $performer->save();

      return redirect()->route('judge.competition.solo-division.show', [$competition, $soloDivision]);
    }


    /**
     * [score description]
     * @param  Competition  $competition  [description]
     * @param  SoloDivision $soloDivision [description]
     * @param  Performer    $performer    [description]
     * @return [type]                     [description]
     */
    public function score(Competition $competition, SoloDivision $soloDivision, Performer $performer)
    {
      $soloDivision->load(['sheet', 'sheet.criteria']);
      $captionsIds = $soloDivision->sheet->caption_ids;
      $captions = Caption::whereIn('id', $captionsIds)->get();
      $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)
                                    ->where('performer_id', $performer->id)
                                    ->where('judge_id', Auth::user()->person_id)
                                    ->get();
      $rankedScores = collect();

      return view('performer.judge.score', compact('competition', 'soloDivision', 'performer', 'captions', 'rawScores', 'rankedScores'));
    }

    /**
     * [storeScore description]
     * @param  Competition  $competition  [description]
     * @param  SoloDivision $soloDivision [description]
     * @param  Performer    $performer    [description]
     * @param  Request      $request      [description]
     * @return [type]                     [description]
     */
    public function storeScore(Competition $competition, SoloDivision $soloDivision, Performer $performer, Request $request)
    {
      $scorekeeper = new SoloScorekeeper(['solo_division_id' => $soloDivision->id]);
      $scorekeeper->performer($performer->id);
      $scorekeeper->judge(Auth::user()->person_id);
      $response = $scorekeeper->save_multiple_scores($request->input('scores'));

      // Redirect
			if($request->input('save_go'))
			{
				return redirect()->route('judge.competition.solo-division.show', [$competition, $soloDivision])->with('success', 'Scores saved!');
			}
			else {
				return redirect()->route('judge.competition.solo-division.performer.score', [$competition, $soloDivision, $performer])->with('success','Scores saved!');
			}
    }
}
