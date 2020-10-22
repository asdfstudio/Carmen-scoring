<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Round;
use App\Judge;
use App\Person;
use App\Caption;
use App\User;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionJudgeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competition_id,$division_id)
    {
        $division = Division::with(['competition','rounds.judges.user','rounds.judges' => function ($query) {
					$query->groupBy('judge_id');
				}, 'judges.captions' => function ($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}])->find($division_id);

				$captions = Caption::forSheet($division->sheet);

        if(!$captions){
          $captions = Collect();
        }

				return view('competition_division_judge.organizer.index', compact('division','captions'));
    }

    public function setup($competition_id,$division_id, FormBuilder $formBuilder)
    {
      $division = Division::with('competition','choirs')->find($division_id);
      $competition = $division->competition;

      $form = $formBuilder->create('Judge\CreateJudgesForm', [
        'url' => route('organizer.competition.division.judge.setup.store',[$competition,$division])
      ]);

      return view('competition_division_judge.organizer.setup', compact('division','competition','form'));
    }



    public function storeMultiple(Request $request, FormBuilder $formBuilder, $competition_id, $division_id)
    {
        $form = $formBuilder->create('Judge\CreateJudgesForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $division = Division::with('competition','judges')->find($division_id);
        $competition = $division->competition;

        foreach($request->input('judges') as $judge_input)
        {

          // Use existing judge
          if(!empty($judge_input['judge_id']))
          {
            $judge_id = $judge_input['judge_id'];
          }
          // Create judge and user login
          elseif(!empty($judge_input['judge']))
          {
            // NEEDS SET UP!
            // Create judge
            // Create user (assign random password)
          }

          // If judge id, attach judge to division
          if($judge_id)
          {
            $caption_id = $judge_input['caption_id'];

            foreach($caption_id as $id)
  					{
  						$extra = NULL;

  						if($id)
  						{
  							$extra = ['caption_id' => $id];
  						}

  						$division->round->judges()->attach($judge_id, $extra);
  					}
          }
        }


				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.index',[$competition,$division]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($competition_id, $division_id, FormBuilder $formBuilder)
    {
				$division = Division::with('competition','choirs')->find($division_id);

        $this->authorize('createJudge', $division);

        $judges = Judge::get();
        $judges = $judges->pluck('full_name', 'id')->toArray();

        $captions = Caption::ForSheet($division->sheet)->pluck('name', 'id')->toArray();
        $form = $formBuilder->create('Judge\ChooseJudgeForm', [
          'class' => '',
					'method' => 'POST',
          'data' => [
            'judges' => $judges,
            'captions' => $captions
          ],
					'url' => route('organizer.competition.division.judge.store',[$division->competition,$division])
				]);

				return view('competition_division_judge.organizer.create', compact('division','form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($competition_id, $round_id, Request $request, FormBuilder $formBuilder)
    {
        // TODO: See why this authorization has been commented out.
        //$this->authorize('create','App\Choir');
				// $form = $formBuilder->create('Judge\ChooseJudgeForm');

				// // Validate input
				// if (!$form->isValid()) {
        //    return redirect()->back()->withErrors($form->getErrors())->withInput();
        // }

				// Get the division
				$round = Round::with('competition','judges')->find($round_id);

        // Create the judge
        if($request->filled('judge.first_name'))
				{

          // Create the judge user login
          try {
            $user = new User;
            $user->username = \App\Http\Controllers\Admin\UserController::generateUsername($request->input('judge.first_name'), $request->input('judge.last_name'));
            $user->email = $request->input('judge.email');
            $user->password = bcrypt('test');

            $judge = new Judge($request->input('judge'));
            $judge->save();

            $person = Person::find($judge->id);
            $person->user()->save($user);

          }
          catch(\Exception $e) {
            if($judge->id !== null) {
              $judge->forceDelete();
            }
            if($request->wantsJson()) {
              $response = [];
              $response['status'] = 'failed';
              $response['errors'] = $e->getMessage();
              return response()->json($response);
            }
            else {
              return redirect()->back()->with('warning',$e->getMessage());
            }
          }


				}
        elseif($request->filled('judge_id'))
        {
          $judge = Judge::find($request->input('judge_id'));
        }
        else {
          $judge = false;
        }

        // Attach the judge to the division and assign captions
				if($judge)
				{
          $caption_id = $request->input('caption_id');

          // -dg- error handling when there is no caption_id
          if(!$caption_id) {
            if($request->wantsJson()) {
              $response = [];
              $response['status'] = 'failed';
              $response['errors'] = 'There are no captions selected! Please select captions!';
              return response()->json($response);
            }
            else {
              return redirect()->back()->with('warning','There are no captions selected! Please select captions!');
            }
          }

					foreach($caption_id as $id)
					{
						$extra = NULL;

						if($id)
						{
							$extra = ['caption_id' => $id];
						}

						$round->judges()->attach($judge->id, $extra);
					}
				}

        $successMessage = $judge->full_name." has been added to this round";


        if($request->wantsJson())
        {
          $judge->load(['captions' => function($query) use ($round_id) {
            $query->wherePivot('round_id', $round_id);
          }]);
          $judge->captions_join = '';
          foreach($judge->captions as $caption) {
            $judge->captions_join .= $caption->id . '-';
          }
          $response = array(
            'status' => 'success',
            'data' => $judge
          );
          return response()->json($response);
        }

        if($request->exists('submit_create_another'))
        {
          return redirect()->back()->with('success',$successMessage);
        }
        else {
          return redirect()->route('organizer.competition.round.show',[$round->competition, $round])->with('success',$successMessage);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($competition_id, $division_id, $judge_id, FormBuilder $formBuilder)
    {
				$division = Division::with('competition')->find($division_id);
        $judge = Judge::find($judge_id);

				$form = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
					'url' => route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge])
				]);

				return view('competition_division_judge.organizer.show', compact('division','judge','form'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($competition_id, $division_id, $judge_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition','choirs')->find($division_id);

        $judge = Judge::with(['captions' => function($query) use ($division_id) {
          $query->where('division_id', $division_id);
        }])->find($judge_id);

        $this->authorize('updateJudge', $division);

        //$judges = $division->round->judges()->where('judge_id',$judge_id)->get();
        //dd($judge->captions->pluck('id')->toArray());

        //dd($judge);
        $captions = Caption::forSheet($division->sheet)->pluck('name', 'id')->toArray();
        $form = $formBuilder->create('Caption\ChooseCaptionForm', [
					'method' => 'PATCH',
          'model' => $judge->captions,
          'data' => [
            'choices' => $captions
          ],
					'url' => route('organizer.competition.division.judge.update',[$division->competition,$division, $judge_id])
				]);

        //dd($judge->captions->pluck('id')->toArray());

        //$form->modify('caption_id','entity',[
        //  'selected' => $judge->captions->pluck('id')->toArray()
        //]);

				$deleteForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE',
					'url' => route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge])
				]);

        $deleteForm->modify('submit','submit',['label' => 'Remove from division']);

				return view('competition_division_judge.organizer.edit', compact('division','form','judge','deleteForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $round_id, $judge_id)
    {
        // Get the Round
        $round = Round::find($round_id);

        // Get the Judge
        $judge = Judge::find($judge_id);

        // Attach the judge to the round and assign captions
        if($judge) {
            $round->judges()->detach($judge->id);
            $caption_id = $request->input('caption_id');

            foreach($caption_id as $id) {
                $extra = NULL;

                if($id)
                {
                    $extra = ['caption_id' => $id];
                }

                $judge->rounds()->attach($round->id, $extra);
            }
        }

        if ($request->wantsJson()) {
            $round_updated = Round::with(['judges' => function ($query) use ($judge_id) {
                $query->where('judge_id', $judge_id)->groupBy('judge_id');
            }, 'judges.captions' => function ($query) use ($round_id) {
                $query->where('round_id',$round_id);
            }])->find($round_id);

            return response()->json($round_updated->judges[0]->captions);
        }
        else {
            return redirect()->route('organizer.competition.round.show',[$round->competition, $round])->with('success',$judge->full_name ." has been updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $round_id, $judge_id, FormBuilder $formBuilder, Request $request)
    {
        $round = Round::with('competition')->find($round_id);
        $round->judges()->detach($judge_id);

        if($request->wantsJson()) {
            return response()->json($judge_id);
        }
        else { // Set flash data and redirect
            return redirect()->route('organizer.competition.round.show',[$round->competition, $round])->with('success', $judge->full_name . ' was successfully removed as a judge for this round.');
        }
    }


    // Import / duplicate / clone judges from another division
    public function import($competition_id, $round_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with('rounds','rounds.judges')->find($competition_id);
      $round = $competition->rounds->firstWhere('id', $round_id);

      $this->authorize('importJudges', $round);

      $rounds = $competition->rounds->whereNotIn('id', [$round_id]);

      return view('competition_division_judge.organizer.import', compact('round', 'rounds', 'competition_id', 'round_id'));
    }


    public function process_import($competition_id, $round_id, Request $request, FormBuilder $formBuilder)
    {
      $competition = Competition::with('rounds')->find($competition_id);
      $round = $competition->rounds->firstWhere('id', $round_id);

      $this->authorize('importJudges', $round);

      // Source round
      $source_round_id = $request->input('id');
      $source_round = Round::find($source_round_id);

      $attachedJudges = array();
      foreach($source_round->judges as $judge)
      {
        if(!$round->judges->contains($judge->id))
        {
          $round->judges()->attach($judge->id, ['caption_id' => $judge->pivot->caption_id]);

          $attachedJudge = Judge::find($judge->id);
          $attachedJudge->load(['captions' => function($query) use ($round_id) {
            $query->wherePivot('round_id', $round_id);
          }]);

          $attachedJudge->captions_join = '';
          foreach($attachedJudge->captions as $caption) {
            $attachedJudge->captions_join .= $caption->id . '-';
          }
          $attachedJudges[] = $attachedJudge;
        }
      }

      if($request->wantsJson())
      {
        return response()->json($attachedJudges);
      }

      return redirect()->route('organizer.competition.round.show', [$competition_id, $round_id])->with('success',"Judges successfully imported from $source_round->name.");
    }
}
