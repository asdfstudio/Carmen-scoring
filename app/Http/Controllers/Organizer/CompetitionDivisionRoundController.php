<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Url;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;
use App\Competition;
use App\Choir;
use App\Round;
use App\RawScore;
use App\Caption;
use App\Judge;
use App\CommentUrl;

use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;
use App\Carmen\CondorcetScores;
use App\Carmen\ConsensusOrdinalRankScores;
use App\Carmen\Scoreboard;
use App\Carmen\Test;
use App\Carmen\Ratings;
use App\Carmen\CountExpectedScores;

use Kris\LaravelFormBuilder\FormBuilder;

use Event;
use App\Events\RoundSaved;
use App\Events\StandingRefreshNeeded;

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

        $this->authorize('create','App\Round',$division);

        $competition_rounds = Competition::find($competition_id)->rounds()->whereHas('division', function ($query) use ($division) {
          $query->where('sheet_id', $division->sheet_id);
        })->get();

        $choices = $competition_rounds->pluck('full_name', 'id')->toArray();
        $selected = [];

        $form = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'POST',
          'class' => 'create-round-form',
          'data' => [
            'choices' => $choices,
            'selected' => $selected,
            'division' => $division
          ],
					'url' => route('organizer.competition.division.round.store', [$division->competition,$division])
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

				$form = $formBuilder->create('Round\CreateRoundForm', [
          'data' => [
            'choices' => [],
            'selected' => [],
            'division' => $division
          ],
        ]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }



				$round = $division->rounds()->save($round);
        $round->sources()->sync($request->input('rounds.id',[]));
        $round->save();

        Event::fire(new RoundSaved($round));

        $successMessage = "$round->name has been added to this division.";

        if($request->wantsJson())
        {
          return response()->json($round);
        }

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
        'choirs',
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

      if(Auth::user()->isAdmin() && isset($_GET['refresh_standings'])){
        Event::fire(new StandingRefreshNeeded($round));
      }

      $division = $round->division;
      $competition = $division->competition;
      $rounds = $division->rounds;
      $divisions = $competition->divisions;
      $judges = $division->judges;
      $choirs = $round->choirs;
      $caption_ids = $division->sheet->caption_ids;
      $captions = Caption::forSheet($division->sheet);
      $ratings = (new Ratings($round))->all();

      $scoreboard = new Scoreboard(['round_id' => $round_id]);
      $rawScores = $scoreboard->extendedRawScores;
      $weightedScores = $scoreboard->extendedRawScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

      $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring', [
          $competition_id,
          $division_id,
          $round_id
        ])
      ]);

      $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring', [
          $competition_id,
          $division_id,
          $round_id
        ])
      ]);

      $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm', [
        'url' => route('organizer.competition.division.round.scoring', [
          $competition_id,
          $division_id,
          $round_id
        ])
      ]);

      $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm', [
        'url' => route('organizer.competition.division.round.scoring', [
          $competition_id,
          $division_id,
          $round_id
        ]),
        'data' => [
          'isMissingScores' => $round->isMissingScores()
        ]
      ]);

      return view('competition_division_round.organizer.show', compact('captions', 'rawScores', 'weightedScores', 'rankedScores', 'round', 'competition', 'division', 'divisions', 'rounds', 'activateScoringForm', 'deactivateScoringForm', 'completeScoringForm', 'reactivateScoringForm', 'scoreboard', 'judges', 'choirs', 'ratings'));
		}




    public function show_sources(Request $request,$competition_id,$division_id,$round_id, FormBuilder $formBuilder)
		{

      $round = Round::with([
        'sources',
        'sources.choirs',
        'division',
        'division.competition',
        //'division.choirs',
        //'division.competition.divisions',
        //'division.rounds',
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

      if(Auth::user()->isAdmin() && isset($_GET['refresh_standings'])){
        Event::fire(new StandingRefreshNeeded($round));
      }

      $division = $round->division;
      $competition = $division->competition;
      $rounds = $division->rounds;
      $divisions = $competition->divisions;

      $caption_ids = $division->sheet->caption_ids;
      $captions = Caption::forSheet($division->sheet);

      $source_division_ids = $round->sources->pluck('division_id')->toArray();

      $source_divisions = Division::with('choirs', 'judges')->whereIn('id', $source_division_ids)->get();

      $source_choirs = collect();
      $source_judges = collect();

      $source_divisions->each(function($item, $key) use ($source_choirs, $source_judges) {
        if($item->has('choirs'))
        {
          $item->choirs->each(function($choir,$key) use ($source_choirs) {
            return $source_choirs->push($choir);
          });
        }

        if($item->has('judges'))
        {
          $item->judges->each(function($judge,$key) use ($source_judges) {
            return $source_judges->push($judge->id);
          });
        }
      });

      $choirs = $source_choirs;


      $judge_ids = $source_judges->unique();
      $judges = Judge::whereIn('id', $judge_ids)->get();

      //dd($judges);

      $source_ids = $round->sources->pluck('id')->toArray();
      $scoreboard = new Scoreboard(['round_id' => $source_ids]);

      //dd($scoreboard);

      $rawScores = $scoreboard->extendedRawScores;
      $weightedScores = $scoreboard->extendedRawScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

      return view('competition_division_round.organizer.show_sources', compact('captions','rawScores', 'weightedScores', 'rankedScores', 'round','competition','division', 'divisions','rounds', 'scoreboard', 'choirs', 'judges'));
		}


    public function scoring($competition_id, $division_id, $round_id, Request $request)
    {
      $round = Round::with('division')->find($round_id);

      // Activate scoring
      if($request->input('activate'))
      {
        $this->authorize('activateScoring', $round);
        $round->division->activateScoring();
        $new_status = 'activated';
      }
      // Deactivate scoring
      elseif($request->input('deactivate'))
      {
        $this->authorize('deactivateScoring', $round);
        $round->division->deactivateScoring();
        $new_status = 'disabled';
      }
      // Reactivate scoring
      elseif($request->input('reactivate'))
      {
        $this->authorize('reactivateScoring', $round);
        $round->division->reactivateScoring();
        $new_status = 'reactivated';
      }
      // Complete and deactivate scoring for all division rounds
      elseif($request->input('complete'))
      {
        $this->authorize('completeScoring', $round);
        $round->division->completeScoring();
        $new_status = 'completed';
      }
      else {
        return false;
      }

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
				$round = Round::with('sources', 'targets')->find($round_id);

        //dd($division->sheet_id);

        // whereHas('divisions', function ($query) use ($division) {
          //$query->where('sheet_id', 2);
        //})->

        $competition_rounds = Competition::find($competition_id )->rounds()->where('rounds.id', '!=', $round_id)->whereHas('division', function ($query) use ($division) {
          $query->where('sheet_id', $division->sheet_id);
        })->get();

        //dd($competition_rounds);

        $choices = $competition_rounds->pluck('full_name', 'id')->toArray();
        $selected = $round->sources->pluck('id')->toArray();

        //dd($choices);
        //dd($selected);

        $this->authorize('update', $round);

        $form = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'PATCH',
          'model' => $round,
          'data' => [
            'choices' => $choices,
            'selected' => $selected,
            'division' => $division
          ],
					'url' => route('organizer.competition.division.round.update', [$competition_id,$division_id, $round_id])
				]);

        $deleteForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
					'url' => route('organizer.competition.division.round.destroy', [$competition_id,$division_id,$round_id])
				]);

        //$deleteForm->modify('submit','submit',['label' => 'Remove from division']);

				return view('competition_division_round.organizer.edit', compact('division', 'round', 'form', 'deleteForm', 'competition_rounds'));
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

        $form = $formBuilder->create('Round\CreateRoundForm', [
          'model' => $round,
          'data' => [
            'choices' => [],
            'selected' => [],
            'division' => $division
          ]
        ]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $round->name = $request->input('name');
        $round->sequence = $request->input('sequence');
        $round->max_choirs = $request->input('max_choirs');
        //$round->fill($request->input());

        $round->sources()->sync($request->input('rounds.id',[]));

        $round->save();

        Event::fire(new RoundSaved($round));

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
