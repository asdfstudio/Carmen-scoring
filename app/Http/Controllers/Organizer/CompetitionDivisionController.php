<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Choir;
use App\Competition;
use App\Division;
use App\Round;
use App\Caption;
use App\Standing;
use App\Judge;
use App\Sheet;
use App\RawScore;

use App\Carmen\Ratings;
use App\Carmen\Scoreboard;

use Kris\LaravelFormBuilder\FormBuilder;

use Event;
use App\Events\DivisionScoringFinalized;

class CompetitionDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competition_id)
    {
        $competition = Competition::with('organization','place','divisions')->find($competition_id);

				return view('competition_division.organizer.index', compact('competition'));
    }



    public function setup($competition_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with('organization','place','divisions','rounds')->find($competition_id);

      $form = $formBuilder->create('Competition\SetupForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.division.setup.store',[$competition])
      ]);

      //dd($competition);
      return view('competition_division.organizer.setup', compact('competition','form'));
    }


    public function storeMultiple(Request $request, $competition_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Competition\SetupForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$competition = Competition::with('organization','place','divisions')->find($competition_id);

        foreach($request->input('divisions') as $division_input)
        {
            $division = new Division($division_input);
            // TODO: Create new divisions in any round
            $competition->rounds->first()->divisions()->save($division);
        }


				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.setup',[$competition]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($competition_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with('organization','place','divisions')->find($competition_id);

        $form = $formBuilder->create('Division\CreateForm', [
            'method' => 'POST',
            'data' => [
                'competition_id' => $competition_id,
            ],
            'url' => route('organizer.competition.division.store',[$competition])
        ]);

        return view('competition_division.organizer.create', compact('competition','form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $competition_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with('organization','place','divisions')->find($competition_id);

        $division = new Division($request->all());
        $division->rating_system = array_filter($request->input('rating_system'));
        $division->save();

        // if($request->exists('submit_create_another'))
        if($request->wantsJson())
        {
          // return redirect()->back()->with('success',$successMessage);
          $data['name'] = $data['new_name'];
          $division_new = new Division($data);
          $division_new->rating_system = array_filter($request->input('rating_system'));
          $competition->divisions()->save($division_new);

          $result = array($division->name, $division_new->name);
          return response()->json($result);
        }
        else {
          $successMessage = "$division->name has been created.";
          return redirect()->route('organizer.competition.division.index', [$competition_id])->with('success',$successMessage);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $division = Division::with(['round', 'competition', 'choirs', 'round.judges' => function ($query) {
            $query->groupBy('judge_id');
        }])->find($division_id);

      // $division = Division::find($division_id);
        // dd($division->round->competition);
      $competition = $division->round->competition;
        // dd($division->round->sheet);
      // dd($division);
      // dd($competition);
      $captions = Caption::forSheet($division->round->sheet);
      $judges = $division->round->judges;
      $choirs = $division->choirs;
      $caption_ids = $division->round->sheet->caption_ids;
      $captions = Caption::forSheet($division->round->sheet);
      $ratings = (new Ratings($division))->all();

      $scoreboard = new Scoreboard(['division_id' => $division_id]);
      $rawScores = $scoreboard->extendedRawScores;
      $weightedScores = $scoreboard->extendedRawScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition_id,$division_id])
        ]);

        $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition_id,$division_id])
        ]);

        $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition_id,$division_id])
        ]);

        $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring', [
            $competition_id,
            $division_id
          ]),
          'data' => [
            'isMissingScores' => $division->isMissingScores()
          ]
        ]);

        // Support for new board view
        // $choirs = Choir::all()->pluck('full_name', 'id')->toArray();

        $newChoirForm = $formBuilder->create('Choir\CreateChoirForm', [
					'method' => 'POST',
                    'data' => Choir::all()->pluck('full_name', 'id')->toArray(),
					'url' => route('organizer.competition.division.choir.store',[$competition_id,$division_id])
				]);

        $selected = [];

        $deleteChoirForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
                  'class' => 'remove-resource'
				]);

        $deleteChoirForm->modify('submit','submit',['label' => 'Remove']);


        // $judges = Judge::get();
        // $judges = $judges->pluck('full_name', 'id')->toArray();

        // $newJudgeForm = $formBuilder->create('Judge\ChooseJudgeForm', [
		// 			'method' => 'POST',
        //             'data' => Judge::all()->pluck('full_name', 'id')->toArray(),
        //             'class' => 'add-judge',
        //             'url' => route('organizer.competition.division.judge.store',[$division->competition,$division])
		// 		]);


        $deleteJudgeForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
                    'class' => 'remove-resource'
				]);

        $deleteJudgeForm->modify('submit','submit',['label' => 'Remove']);


        $newPenaltyForm = $formBuilder->create('Penalty\CreatePenaltyForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.penalty.store', [$competition_id, $division_id])
        ]);


        $deletePenaltyForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
          'class' => 'remove-resource'
				]);

        $deletePenaltyForm->modify('submit','submit',['label' => 'Remove']);

        $include_division_navigation_bar = TRUE;
        // return view('competition_division.organizer.show', compact('competition', 'division', 'captions', 'activateScoringForm', 'reactivateScoringForm', 'deactivateScoringForm', 'completeScoringForm', 'newChoirForm', 'newRoundForm', 'deleteChoirForm', 'deleteJudgeForm', 'newJudgeForm', 'newPenaltyForm', 'deletePenaltyForm'));
        return view('competition_division.organizer.show', compact('competition', 'include_division_navigation_bar', 'division', 'judges', 'choirs', 'captions', 'scoreboard', 'rawScores', 'weightedScores', 'rankedScores', 'activateScoringForm', 'reactivateScoringForm', 'deactivateScoringForm', 'completeScoringForm', 'newChoirForm', 'deleteChoirForm', 'deleteJudgeForm', 'newPenaltyForm', 'deletePenaltyForm'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function board($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $division = Division::with(['competition', 'choirs', 'choirs.directors','round','round.judges', 'round.judges.captions'])->find($division_id);

        $captions = Caption::forSheet($division->sheet);
        $competition = $division->competition;

        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);

        $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition_id,$division_id])
        ]);

        $finalizeScoringForm = $formBuilder->create('Scoring\FinalizeScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition_id,$division_id])
        ]);


        // Support for new board view
        $choirs = Choir::all()->pluck('full_name', 'id')->toArray();

        //dd($choirs);

        $newChoirForm = $formBuilder->create('Choir\CreateChoirForm', [
					'method' => 'POST',
          'data' => $choirs,
					'url' => route('organizer.competition.division.choir.store',[$division->competition,$division])
				]);

        return view('competition_division.organizer.board', compact('competition', 'division', 'newChoirForm'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with('organization', 'place', 'divisions')->find($competition_id);

        $division = Division::find($division_id);
        $division->load('competition', 'awardSettings', 'round', 'round.sheet', 'round.sheet.criteria', 'round.sheet.criteria.caption');

        if ($division->round->sheet) {
            $division->round->sheet->captions = $division->round->sheet->criteria->unique('caption_id')->pluck('caption');
        }

        return view('competition_division.organizer.settings', compact('competition','division'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition', 'competition.organization', 'competition.place', 'round')
            ->find($division_id);

        $this->authorize('update', $division);

        $competition = $division->competition;

        $form = $formBuilder->create('Division\CreateForm', [
            'method' => 'PUT',
            'model' => $division,
            'url' => route('organizer.competition.division.update', [$competition, $division_id]),
            'data' => [
                'competition_id' => $competition_id,
            ],
        ]);

        $deleteForm = $formBuilder->create('GenericDeleteForm', [
            'url' => route('organizer.competition.division.destroy', [$competition, $division])
        ]);

        $updating = TRUE;

        return view('competition_division.organizer.create', compact('competition', 'division', 'form', 'updating', 'deleteForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $competition_id, $division_id, FormBuilder $formBuilder)
    {
        $division = Division::find($division_id);

        $this->authorize('update', $division);

        // Validate input
        $form = $formBuilder->create('Division\CreateForm');
        if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $division->fill($request->all());
        $division->rating_system = array_filter($request->input('rating_system'));

        $division->save();

        if($request->wantsJson()) // save & create new division
        {
            $competition = Competition::find($competition_id);
            $data['name'] = $data['new_name'];
            $division_new = new Division($data);
            $division_new->rating_system = array_filter($request->input('rating_system'));
            $competition->divisions()->save($division_new);

            $result = array('edited' => $division->name, 'new' => $division_new->name);
            return response()->json($result);
        } else {
            return redirect()->route('organizer.competition.division.index', [$competition_id])->with('success', "$division->name has been updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $division_id)
    {
      $division = Division::find($division_id);

      $this->authorize('destroy', $division);

      $division->delete();

      return redirect()->route('organizer.competition.division.index',[$competition_id])->with('success', "$division->name successfully deleted.");
    }


    public function scoring($competition_id, $division_id, Request $request)
    {
      $division = Division::with('rounds')->find($division_id);

      // Activate scoring for
      //all of the division rounds for this competition
      if($request->input('activate'))
      {
        $division->activateScoring();
      }
      // Reactivate scoring for all division rounds
      elseif($request->input('reactivate'))
      {
        $division->reactivateScoring();
      }
      // Deactivate scoring for all division rounds
      elseif($request->input('deactivate'))
      {
        $division->deactivateScoring();
      }
      // Complete scoring for all division rounds
      elseif($request->input('complete'))
      {
        $division->completeScoring();
      }
      elseif($request->input('finalize'))
      {
        $division->finalizeScoring();
        event(new DivisionScoringFinalized($division));
      }
      else {
        return 0;
      }

      return redirect()->route('organizer.competition.division.settings',[$competition_id,$division_id]);
    }
}
