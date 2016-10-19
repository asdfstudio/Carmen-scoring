<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;
use App\Competition;
use App\Choir;
use App\Round;
use App\RawScore;
use App\Caption;

use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionRoundController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $this->authorize('showAll', 'App\Round');

        $division = Division::with('competition','rounds')->find($division_id);

        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm');

        $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm');

        $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm');

        $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm');

        return view('competition_division_round.organizer.index', compact('division','activateScoringForm', 'deactivateScoringForm', 'reactivateScoringForm', 'completeScoringForm' ));
    }



    public function setup($competition_id,$division_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition','rounds')->find($division_id);
        $competition = $division->competition;

        $form = $formBuilder->create('Round\CreateRoundsForm', [
          'url' => route('organizer.competition.division.round.setup.store',[$competition,$division])
        ]);

        return view('competition_division_round.organizer.setup', compact('division','competition','form'));
    }


    public function storeMultiple(Request $request, FormBuilder $formBuilder, $competition_id, $division_id)
    {
        $form = $formBuilder->create('Round\CreateRoundsForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $division = Division::with('competition','rounds')->find($division_id);
        $competition = $division->competition;

        foreach($request->input('rounds') as $round_input)
        {
          $round = new Round($round_input);
          $division->rounds()->save($round);
        }


				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.round.setup',[$competition,$division]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($competition_id, $division_id, FormBuilder $formBuilder)
    {
				$division = Division::with('competition','rounds')->find($division_id);

        $this->authorize('create', ['App\Round', $division]);

        $form = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'POST',
					'url' => route('organizer.competition.division.round.store',[$division->competition,$division])
				]);

				return view('competition_division_round.organizer.create', compact('division','form'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($competition_id, $division_id, Request $request, FormBuilder $formBuilder)
    {
        // Get the division
        $division = Division::with('competition','rounds')->find($division_id);

        $this->authorize('create', ['App\Round', $division]);

        $round = new Round($request->all());

				$form = $formBuilder->create('Round\CreateRoundForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$division->rounds()->save($round);


        $successMessage = "$round->name has been added to this division.";

        if($request->exists('submit_create_another'))
        {
          return redirect()->back()->with('success',$successMessage);
        }
        else {
          return redirect()->route('organizer.competition.division.round.index',[$division->competition, $division])->with('success',$successMessage);
        }
    }



    public function show(Request $request,$competition_id,$division_id,$round_id, FormBuilder $formBuilder)
		{
      $round = Round::with([
        'division',
        'division.competition',
        'division.choirs',
        'division.competition.divisions',
        'division.rounds',
        'division.judges' => function($query) {
					//$query->where('judge_id',$judge_id)->first();
          $query->groupBy('judge_id');
          //$query->distinct('id');
				},
        'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				},
        'division.judges.captions.criteria'
      ])->find($round_id);

      $this->authorize('show', $round);

      $division = $round->division;

      //dd($division->judges);

      $competition = $division->competition;
      $rounds = $division->rounds;
      $divisions = $competition->divisions;

      $captions = Caption::get();

			$rawScores = RawScore::with('judge','choir','criterion')->where('division_id',$division_id)->where('round_id',$round_id)->get();

      $weightedScoresClass = new WeightedScores($rawScores,        $division->caption_weighting_id);

      $weightedScores = $weightedScoresClass->all();

      $rankedScores = new RankedScores($weightedScores);

      $rank = $rankedScores->rank(38);

      $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring',[$competition_id,$division_id,$round_id])
      ]);

      $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring',[$competition_id,$division_id,$round_id])
      ]);

      $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm', [
        'url' => route('organizer.competition.division.round.scoring',[$competition_id,$division_id,$round_id])
      ]);

      $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring',[$competition_id,$division_id,$round_id])
      ]);


      return view('competition_division_round.organizer.show', compact('captions','rawScores', 'weightedScores', 'rankedScores', 'round','competition','division','divisions','rounds','activateScoringForm', 'deactivateScoringForm', 'completeScoringForm', 'reactivateScoringForm'));
		}


    public function scoring($competition_id, $division_id, $round_id, Request $request)
    {
      $round = Round::find($round_id);

      // Activate scoring
      if($request->input('activate'))
      {
        $this->authorize('activateScoring', $round);
        $is_scoring_active = true;
        $is_completed = false;
        $new_status = 'enabled';
      }
      // Deactivate scoring
      elseif($request->input('deactivate'))
      {
        $this->authorize('deactivateScoring', $round);
        $is_scoring_active = false;
        $is_completed = false;
        $new_status = 'disabled';
      }
      // Reactivate scoring
      elseif($request->input('reactivate'))
      {
        $this->authorize('reactivateScoring', $round);
        $is_scoring_active = true;
        $is_completed = false;
        $new_status = 'reactivated';
      }
      // Complete and deactive scoring for all division rounds
      elseif($request->input('complete'))
      {
        $this->authorize('completeScoring', $round);
        $is_scoring_active = false;
        $is_completed = true;
        $new_status = 'completed';
      }
      else {
        return false;
      }

      $round->is_scoring_active = $is_scoring_active;
      $round->is_completed = $is_completed;
      $round->save();

      return redirect()->route('organizer.competition.division.round.index',[$competition_id,$division_id])->with('success', "Scoring for $round->name is now $new_status.");
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($competition_id, $division_id, $round_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition','rounds')->find($division_id);
				$round = Round::find($round_id);

        $this->authorize('update', $round);

        $form = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'PATCH',
          'model' => $round,
					'url' => route('organizer.competition.division.round.update', [$competition_id,$division_id, $round_id])
				]);

        $deleteForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
					'url' => route('organizer.competition.division.round.destroy', [$competition_id,$division_id,$round_id])
				]);

        //$deleteForm->modify('submit','submit',['label' => 'Remove from division']);

				return view('competition_division_round.organizer.edit', compact('division','round','form', 'deleteForm'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $division_id, $round_id)
    {
        // Get the division
        $division = Division::find($division_id);

        // Get the round
        $round = Round::find($round_id);

        $this->authorize('update', $round);

        $form = $formBuilder->create('Round\CreateRoundForm', ['model' => $round]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $round->name = $request->input('name');
        $round->save();

        return redirect()->route('organizer.competition.division.round.index',[$division->competition, $division])->with('success',$round->name ." has been updated.");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $division_id, $round_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition')->find($division_id);
        $round = Round::find($round_id);

        $this->authorize('destroy', $round);

        $round->delete();
				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.round.index', [$division->competition, $division])->with('success', $round->name . ' was successfully removed from this division.');
    }

}
