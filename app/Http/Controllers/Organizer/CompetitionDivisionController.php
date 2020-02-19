<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Choir;
use App\Competition;
use App\Division;
use App\Caption;
use App\Standing;
use App\Judge;
use App\RawScore;
use App\Carmen\CountExpectedScores;

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
				//dd($competition);
        //

        //$division = new Division;

				return view('competition_division.organizer.index', compact('competition'));
    }



    public function setup($competition_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with('organization','place','divisions','divisions.rounds')->find($competition_id);

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
          $competition->divisions()->save($division);
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
        $form = $formBuilder->create('Division\CreateForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$competition = Competition::with('organization','place','divisions')->find($competition_id);

        //dd($request->all());

				$division = new Division($request->all());
        $division->rating_system = array_filter($request->input('rating_system'));

				$competition->divisions()->save($division);

        $successMessage = "$division->name has been created.";

        if($request->exists('submit_create_another'))
        {
          return redirect()->back()->with('success',$successMessage);
        }
        else {
          return redirect()->route('organizer.competition.division.index', [$competition])->with('success',$successMessage);
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
        $competition = Competition::with('organization','place','divisions')->find($competition_id);

				$division = Division::with(['choirs','rounds','judges' => function ($query) {
					$query->groupBy('judge_id');
				}, 'judges.captions' => function ($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}])->find($division_id);

				$captions = Caption::forSheet($division->sheet);

        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);

        $reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);

        $deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
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

        $finalizeScoringForm = $formBuilder->create('Scoring\FinalizeScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring', [
            $competition_id,
            $division_id
          ])
        ]);


        // Support for new board view
        $choirs = Choir::all()->pluck('full_name', 'id')->toArray();

        //dd($choirs);

        $newChoirForm = $formBuilder->create('Choir\CreateChoirForm', [
					'method' => 'POST',
          'data' => $choirs,
					'url' => route('organizer.competition.division.choir.store',[$division->competition,$division])
				]);

        $competition_rounds = Competition::find($competition_id )->rounds()->whereHas('division', function ($query) use ($division) {
          $query->where('sheet_id', $division->sheet_id);
        })->get();

        $choices = $competition_rounds->pluck('full_name', 'id')->toArray();
        $selected = [];


        $newRoundForm = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'POST',
          'data' => [
            'choices' => $choices,
            'selected' => $selected,
            'division' => $division
          ],
					'url' => route('organizer.competition.division.round.store', [$division->competition,$division])
				]);

        $deleteChoirForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
          'class' => 'remove-resource'
				]);

        $deleteChoirForm->modify('submit','submit',['label' => 'Remove']);


        $judges = Judge::get();
        $judges = $judges->pluck('full_name', 'id')->toArray();

        $newJudgeForm = $formBuilder->create('Judge\ChooseJudgeForm', [
					'method' => 'POST',
          'data' => $judges,
					'url' => route('organizer.competition.division.judge.store',[$division->competition,$division])
				]);


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

        return view('competition_division.organizer.show', compact('competition', 'division', 'captions', 'activateScoringForm', 'reactivateScoringForm', 'deactivateScoringForm', 'completeScoringForm', 'finalizeScoringForm', 'newChoirForm', 'newRoundForm', 'deleteChoirForm', 'deleteJudgeForm', 'newJudgeForm', 'newPenaltyForm', 'deletePenaltyForm'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function board($competition_id, $division_id, FormBuilder $formBuilder)
    {
        $competition = Competition::with('organization','place','divisions')->find($competition_id);

				$division = Division::with(['choirs','rounds','judges' => function ($query) {
					$query->groupBy('judge_id');
				}, 'judges.captions' => function ($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}])->find($division_id);

				$captions = Caption::forSheet($division->sheet);

        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);

        /*$deactivateScoringForm = $formBuilder->create('Scoring\DeactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);*/

        /*$reactivateScoringForm = $formBuilder->create('Scoring\ReactivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.division.scoring',[$competition,$division])
        ]);*/

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

        $competition_rounds = Competition::find($competition_id )->rounds()->whereHas('division', function ($query) use ($division) {
          $query->where('sheet_id', $division->sheet_id);
        })->get();

        $choices = $competition_rounds->pluck('full_name', 'id')->toArray();
        $selected = [];


        $newRoundForm = $formBuilder->create('Round\CreateRoundForm', [
					'method' => 'POST',
          'data' => [
            'choices' => $choices,
            'selected' => $selected,
            'division' => $division
          ],
					'url' => route('organizer.competition.division.round.store', [$division->competition,$division])
				]);

        $deleteChoirForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
          'class' => 'remove-resource'
				]);

        $deleteChoirForm->modify('submit','submit',['label' => 'Remove']);


        $judges = Judge::get();
        $judges = $judges->pluck('full_name', 'id')->toArray();

        $newJudgeForm = $formBuilder->create('Judge\ChooseJudgeForm', [
					'method' => 'POST',
          'data' => $judges,
					'url' => route('organizer.competition.division.judge.store',[$division->competition,$division])
				]);


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

        //
				//return view('competition_division.organizer.show', compact('competition', 'division', 'captions', 'activateScoringForm', 'publishScoringForm', 'completeScoringForm', 'finalizeScoringForm'));



        return view('competition_division.organizer.board', compact('competition', 'division', 'captions', 'activateScoringForm', 'completeScoringForm', 'finalizeScoringForm', 'newChoirForm', 'newRoundForm', 'deleteChoirForm', 'deleteJudgeForm', 'newJudgeForm', 'newPenaltyForm', 'deletePenaltyForm'));
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
        $division->load('competition', 'awardSettings', 'sheet', 'sheet.criteria', 'sheet.criteria.caption');

        if ($division->sheet) {
          $division->sheet->captions = $division->sheet->criteria->unique('caption_id')->pluck('caption');
        }


        //
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
        $competition = Competition::with('organization','place','divisions')->find($competition_id);
				$division = Division::find($division_id);

        $this->authorize('update', $division);

        //dd($division->overall_award_sponsors);

        $form = $formBuilder->create('Division\CreateForm', [
					'method' => 'PUT',
					'model' => $division,
					'url' => route('organizer.competition.division.update',[$competition,$division_id])
				]);


        $deleteForm = $formBuilder->create('GenericDeleteForm', [
          'url' => route('organizer.competition.division.destroy',[$competition,$division])
        ]);

				return view('competition_division.organizer.edit', compact('competition','division','form', 'deleteForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $competition, $division_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Division\CreateForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				//$competition = Competition::find($competition_id);
				$division = Division::find($division_id);

        $this->authorize('update', $division);

        $data = $request->all();

				$division->fill($data);
        $division->rating_system = array_filter($request->input('rating_system'));
				$division->save();

				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.settings',[$competition, $division])->with('success',"$division->name has been updated.");
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

      return redirect()->route('organizer.competition.show',[$competition_id])->with('success', "$division->name successfully deleted.");
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
        Event::fire(new DivisionScoringFinalized($division));
      }
      else {
        return 0;
      }

      return redirect()->route('organizer.competition.division.show',[$competition_id,$division_id]);
    }
}
