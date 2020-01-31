<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
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
        $division = Division::with(['competition','judges.user','judges' => function ($query) {
					$query->groupBy('judge_id');
				}, 'judges.captions' => function ($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}])->find($division_id);

        //dd($division->judges);

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

  						$division->judges()->attach($judge_id, $extra);
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

        $form = $formBuilder->create('Judge\ChooseJudgeForm', [
          'class' => '',
					'method' => 'POST',
          'data' => $judges,
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
    public function store($competition_id, $division_id, Request $request, FormBuilder $formBuilder)
    {
        //$this->authorize('create','App\Choir');
				$form = $formBuilder->create('Judge\ChooseJudgeForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Get the division
				$division = Division::with('competition','judges')->find($division_id);

        // Create the judge
        if($request->filled('judge.first_name'))
				{
					$judge = new Judge($request->input('judge'));
          $judge->save();

          // Create the judge user login
          $user = new User;
          $user->username = \App\Http\Controllers\Admin\UserController::generateUsername($request->input('judge.first_name'), $request->input('judge.last_name'));
          $user->email = $request->input('judge.email');
          $user->password = bcrypt('test');
          
          $person = Person::find($judge->id);
          $person->user()->save($user);

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

					foreach($caption_id as $id)
					{
						$extra = NULL;

						if($id)
						{
							$extra = ['caption_id' => $id];
						}

						$division->judges()->attach($judge->id, $extra);
					}
				}

        $successMessage = $judge->full_name." has been added to this division.";


        if($request->wantsJson())
        {
          $judge->load(['captions' => function($query) use ($division_id) {
            $query->wherePivot('division_id', $division_id);
          }]);
          return response()->json($judge);
        }

        if($request->exists('submit_create_another'))
        {
          return redirect()->back()->with('success',$successMessage);
        }
        else {
          return redirect()->route('organizer.competition.division.judge.index',[$division->competition, $division])->with('success',$successMessage);
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

        //$judges = $division->judges()->where('judge_id',$judge_id)->get();
        //dd($judge->captions->pluck('id')->toArray());

        //dd($judge);

        $form = $formBuilder->create('Caption\ChooseCaptionForm', [
					'method' => 'PATCH',
					'model' => $judge->captions,
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
    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $division_id, $judge_id)
    {
        // Get the division
        $division = Division::find($division_id);

        // Get the Judge
        $judge = Judge::find($judge_id);

        $form = $formBuilder->create('Caption\ChooseCaptionForm', ['model' => $judge->captions]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }



        //dd($division);
        //dd($judge);

        // Attach the judge to the division and assign captions
				if($judge)
				{
					$caption_id = $request->input('caption_id');

          //$division->judges()->detach($judge->id);

          $judge->divisions()->detach($division->id);

					foreach($caption_id as $id)
					{
						$extra = NULL;

						if($id)
						{
							$extra = ['caption_id' => $id];
						}

            $judge->divisions()->attach($division->id, $extra);
						//$division->judges()->attach($judge->id, $extra);
					}
				}



        return redirect()->route('organizer.competition.division.judge.index',[$division->competition, $division])->with('success',$judge->full_name ." has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $division_id, $judge_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition')->find($division_id);
        $judge = Judge::find($judge_id);

				$division->judges()->detach($judge_id);

				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.judge.index',[$division->competition, $division])->with('success', $judge->full_name . ' was successfully removed as a judge for this division.');
    }


    // Import / duplicate / clone judges from another division
    public function import($competition_id, $division_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with('divisions','divisions.judges')->find($competition_id);
      $division = Division::with('competition')->find($division_id);

      $this->authorize('importJudges', $division);

      $divisions = $competition->divisions->reject(function($value,$key) use ($division_id) {
        return $value->id == $division_id;
      });

      $data = [
        'choices' => $competition->divisions->reject(function($value,$key) use ($division_id) {
          return $value->id == $division_id;
        })->pluck('name', 'id')->toArray()
      ];

      $form = $formBuilder->create('Division\ChooseDivisionForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.division.judge.import.process', [$competition_id, $division_id]),
        'data' => $data
      ]);

      return view('competition_division_judge.organizer.import', compact('division', 'form', 'divisions'));
    }


    public function process_import($competition_id, $division_id, Request $request, FormBuilder $formBuilder)
    {
      $competition = Competition::with('divisions')->find($competition_id);
      $division = Division::with('competition')->find($division_id);

      $this->authorize('importJudges', $division);

      // Source division
      $source_division_id = $request->input('id');
      $source_division = Division::find($source_division_id);

      foreach($source_division->judges as $judge)
      {
        if(!$division->judges->contains($judge->id))
        {
          $division->judges()->attach($judge->id, ['caption_id' => $judge->pivot->caption_id]);
        }
      }

      return redirect()->route('organizer.competition.division.judge.index', [$competition_id, $division_id])->with('success',"Judges successfully imported from $source_division->name.");
    }
}
