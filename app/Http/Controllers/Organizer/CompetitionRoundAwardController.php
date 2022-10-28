<?php

namespace App\Http\Controllers\Organizer;

use App\Award;
use App\Competition;
use App\Http\Controllers\Controller;
use App\Round;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

use Auth;
use DB;

class CompetitionRoundAwardController extends Controller
{
    public function index($competition_id, $round_id)
    {
      $round = Round::with(['competition', 'awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->find($round_id);

      $this->authorize('showAll', 'App\Award');

      $competition = $round->competition;
      $awards = $round->awards;

      return view('competition_round_award.organizer.index', compact('competition', 'round', 'awards'));
    }

    public function create(FormBuilder $formBuilder, $competition_id, $round_id)
    {
      $round = Round::find($round_id);

      $this->authorize('create', ['App\Award', $round]);

      $form = $formBuilder->create('Award\CreateAwardForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.round.award.store', [$competition_id, $round_id]),
        'data' => ['include_sponsor' => true]
      ]);

      return view('competition_round_award.organizer.create', compact('form', 'round'));
    }

    public function store(Request $request, FormBuilder $formBuilder, $competition_id, $round_id)
    {
      $round = Round::find($round_id);

      $this->authorize('create', ['App\Award', $round]);

      $form = $formBuilder->create('Award\CreateAwardForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // DB transaction
      $award = DB::transaction(function () use ($data, $round, $request) {

        // Create the award
        $award = Award::create($data);
        $award->save();

        // Associate award with round
        $extra = [];

        if($request->input('sponsor'))
        {
          $extra['sponsor'] = $request->input('sponsor');
        }

        $round->awards()->attach($award->id, $extra);

        return $award;
      });

      $successMessage = "$award->name has been created and added to this round.";

      if($request->exists('submit_create_another'))
      {
        return redirect()->back()->with('success',$successMessage);
      }
      else {
        return redirect()->route('organizer.competition.round.award.index', [$competition_id, $round_id])->with('success',$successMessage);
      }
    }

    public function manage(FormBuilder $formBuilder, Competition $competition, $round_id)
    {
      $round = $competition->rounds()->with(['awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->findOrFail($round_id);

      $selected_awards = $round->awards;

      $this->authorize('manage', ['App\Award', $round]);

      $awards = Award::where('organization_id',    Auth::user()->organization_id)->get();

      $standard_awards = Award::withoutGlobalScope('organization')->where('organization_id', NULL)->get();

      return view('competition_round_award.organizer.manage', compact('competition','round', 'awards', 'standard_awards','selected_awards'));
    }

    public function update(Request $request, Competition $competition, $round_id)
    {
      $round = $competition->rounds()->findOrFail($round_id);

      $this->authorize('manage', ['App\Award', $round]);

      $awards = $request->input('awards', []);
      $sponsors = $request->input('sponsors', []);

      $data = [];

      foreach($awards as $award_id)
      {
        $data[$award_id] = ['sponsor' => $sponsors[$award_id]];
      }


      $round->awards()->sync($data);

      // Set flash data and redirect
      return redirect()->route('organizer.competition.round.award.index', [$competition, $round]);
    }

    public function assign(FormBuilder $formBuilder, Competition $competition, $round_id)
    {
      $round = $competition->rounds()->with('choirs.school')->findOrFail($round_id);
      $awards = $round->awards;

      $this->authorize('assign', ['App\Award', $round]);

      return view('competition_round_award.organizer.assign', compact('competition','round', 'awards'));
    }

    public function update_assignment(Request $request, Competition $competition, $round_id)
    {
      $round = $competition->rounds()->findOrFail($round_id);
      $this->authorize('assign', ['App\Award', $round]);

      // Force empty choir_id to be null, not a blank string;
      $awards = collect($request->input('awards', []))->map(function($item, $key) {
          if (!$item['choir_id']) {
              $item['choir_id'] = NULL;
          }
          return $item;
      });

      $round->awards()->sync($awards);

      return redirect()->route('organizer.competition.round.award.index', [$competition, $round_id])->with('success', 'Award assignments updated.');
    }

}
