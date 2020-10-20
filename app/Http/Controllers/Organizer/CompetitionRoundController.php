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

class CompetitionRoundController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competition_id, FormBuilder $formBuilder)
    {
        $this->authorize('showAll', 'App\Round');

        $competition = Competition::find($competition_id);

        // $division = Division::with('competition','rounds')->find($division_id);

        // $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm');
        //
        // $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm');
        //
        // $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm');
        //
        // $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm');
        //
        // $finalizeScoringFormData = [
        //   'method' => 'POST',
        //   'url' => route('organizer.competition.round.scoring', [
        //       $competition_id,
        //       // $round_id
        //   ]),
        // ];

        // if($division->status_slug() != 'completed') {
        //   $finalizeScoringFormData['disabled'] = true;
        // }
        //
        // $finalizeScoringForm = $formBuilder->create('Scoring\FinalizeScoringForm', $finalizeScoringFormData);

        return view('competition_round.organizer.index', compact('competition'));
        // 'division','activateScoringForm', 'finalizeScoringForm', 'deactivateScoringForm', 'reactivateScoringForm', 'completeScoringForm' ));
    }



    public function setup($competition_id,FormBuilder $formBuilder)
    {
        $competition = $division->competition;

        $form = $formBuilder->create('Round\CreateRoundsForm', [
          'url' => route('organizer.competition.round.setup.store',[$competition])
        ]);

        return view('competition.round.organizer.setup', compact('competition','form'));
    }


    public function storeMultiple(Request $request, FormBuilder $formBuilder, $competition_id)
    {
        $form = $formBuilder->create('Round\CreateRoundsForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $competition = Competition::find($competition_id);

        foreach($request->input('rounds') as $round_input)
        {
          $round = new Round($round_input);
          $competition->rounds()->save($round);
        }


				// Set flash data and redirect
				return redirect()->route('organizer.competition.round.setup',[$competition]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($competition_id, FormBuilder $formBuilder)
    {
        $competition = Competition::find($competition_id);
        $this->authorize('create','App\Round', $competition);
        $selected = [];

        $form = $formBuilder->create('Round\CreateRoundForm', [
            'method' => 'POST',
            'class' => 'create-round-form',
            'data' => [
                'selected' => $selected,
            ],
            'url' => route('organizer.competition.round.store', [$competition])
        ]);

        return view('competition_round.organizer.create', compact('competition','form'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($competition_id, Request $request, FormBuilder $formBuilder)
    {
        $competition = Competition::find($competition_id);
        $this->authorize('create', ['App\Round', $competition]);

        $round = new Round($request->all());
        $form = $formBuilder->create('Round\CreateRoundForm', [
            'model' => $round
        ]);

        // Validate input
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $round->competition()->associate($competition);
        $round->save();

        // TODO: Check out which events should actually be running
        // event(new RoundSaved($round));

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
          return redirect()->route('organizer.competition.round.index',[$competition])->with('success',$successMessage);
        }
    }



    public function show(Request $request,$competition_id,$round_id, FormBuilder $formBuilder)
		{

      $round = Round::with([
          'competition',
          'sheet',
          'divisions',
          'judges',
          // 'divisions.choirs',
          // 'divisions.judges',
          // 'divisions.judges.captions',
          // 'divisions.judges.captions.criteria'
      ])->find($round_id);

      $this->authorize('show', $round);

      // if(Auth::user()->isAdmin() && isset($_GET['refresh_standings'])){
      //   event(new StandingRefreshNeeded($round));
      // }

      $competition = $round->competition;
      $captions = Caption::all();
      $judges = $round->judges;
      $division = $round->divisions->first();

      return view('competition_round.organizer.show', compact('captions', 'judges', 'competition', 'round', 'division' ));
      // return view('competition.round.organizer.show', compact(/* 'captions', /*'rawScores', /* 'weightedScores', 'rankedScores',*/ 'round', 'competition', 'divisions', // 'rounds', )); //'scoreboard'));
		}


    /**
     * Display the Scoring Setting for the Round.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings($competition_id, $round_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with(['organization', 'place', 'rounds' => function($query) use ($round_id) {
            $query->find($round_id);
        }, 'rounds.sheet', 'rounds.sheet.criteria', 'rounds.sheet.criteria.caption'])->find($competition_id);

        $round = $competition->rounds->first();
        $unique_captions = $round->sheet->captions = $round->sheet->criteria->unique('caption_id')->pluck('caption');

        return view('competition_round.organizer.settings', compact('competition','round'));
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
        event(new StandingRefreshNeeded($round));
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

      return view('competition.round.organizer.show_sources', compact('captions','rawScores', 'weightedScores', 'rankedScores', 'round','competition','division', 'divisions','rounds', 'scoreboard', 'choirs', 'judges'));
		}


    public function scoring($competition_id, Request $request)
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

      return redirect()->route('organizer.competition.round.index',[$competition_id])->with('success', "Scoring for $round->name is now $new_status.");
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($competition_id, $round_id, FormBuilder $formBuilder)
    {
        $round = Round::with('competition')->find($round_id);
        $this->authorize('update', $round);
        $selected = [];

        $form = $formBuilder->create('Round\CreateRoundForm', [
            'method' => 'PATCH',
            'model' => $round,
            'class' => 'edit-round-form',
            'data' => [
                'selected' => $selected
            ],
            'url' => route('organizer.competition.round.update', [$competition_id,$round_id])
        ]);

        $deleteForm = $formBuilder->create('GenericDeleteForm', [
            'method' => 'DELETE',
            'url' => route('organizer.competition.round.destroy', [$competition_id,$round_id])
        ]);


        return view('competition_round.organizer.edit', compact('round', 'form', 'deleteForm', ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $round_id)
    {
        $round = Round::with('competition')->find($round_id);
        // $competition = Competition::find($competition_id);
        $this->authorize('update', $round);

        $round = new Round($request->all());

        // $round = new Round($request->all());
        $form = $formBuilder->create('Round\CreateRoundForm', [
            'data' => [
                'choices' => [],
                'selected' => []
            ],
        ]);

        // Validate input
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $round->competition()->associate($competition);
        $round->save();

        // TODO: Check out which events should actually be running
        // event(new RoundSaved($round));

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
          return redirect()->route('organizer.competition.round.index',[$competition])->with('success',$successMessage);
        }

        $round->name = $request->input('name');
        $round->sequence = $request->input('sequence');
        $round->max_choirs = $request->input('max_choirs');
        //$round->fill($request->input());

        $round->sources()->sync($request->input('rounds.id',[]));

        $round->save();

        event(new RoundSaved($round));

        return redirect()->route('organizer.competition.round.index',[$division->competition, $division])->with('success',$round->name ." has been updated.");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $round_id, FormBuilder $formBuilder)
    {
        $round = Round::find($round_id);

        $this->authorize('destroy', $round);

        $round->delete();
				// Set flash data and redirect
				return redirect()->route('organizer.competition.round.index', [$division->competition, $division])->with('success', $round->name . ' was successfully removed from this division.');
    }

    /**
     * Display the judge board.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function board($competition_id, $round_id, FormBuilder $formBuilder)
    {
        $round = Round::with(['competition', 'divisions', 'judges', 'judges.captions'])->find($round_id);

        $captions = Caption::forSheet($round->sheet);
        $competition = $round->competition;
        // TODO: Remove this from the template. No need for divisions here.
        $division = $round->divisions->first();

        $judges = Judge::get();
        $judges = $judges->pluck('full_name', 'id')->toArray();

        $newJudgeForm = $formBuilder->create('Judge\ChooseJudgeForm', [
            'method' => 'POST',
            'data' => [
                'judges' => $judges,
                'captions' => $captions->pluck('name', 'id')->toArray()
            ],
            'url' => route('organizer.competition.division.judge.store',[$round->competition,$round])
        ]);

        // TODO: Change to $rounds_import_judge
        $competition_import_judge = Competition::with('rounds.judges')->find($competition_id);
        $round_id = $round->id;

        $rounds_import_judge = $competition_import_judge->rounds->reject(function($value, $key) use ($round_id) {
          return $value->id == $round_id;
        });

        return view('competition_round.organizer.board', compact('competition', 'round', 'division', 'captions', 'rounds_import_judge', 'newJudgeForm'));
    }
}
