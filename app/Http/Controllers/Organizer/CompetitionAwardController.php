<?php

namespace App\Http\Controllers\Organizer;

use App\Award;
use App\Competition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionAwardController extends Controller
{
    public function create(FormBuilder $formBuilder, $competition_id)
    {
      $competition = Competition::find($competition_id);

      $this->authorize('create', ['App\Award', $competition]);

      $form = $formBuilder->create('Award\CreateAwardForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.award.store', [$competition_id]),
        'data' => ['include_sponsor' => true]
      ]);

      return view('competition_award.organizer.create', compact('form', 'competition'));
    }

    public function store(Request $request, FormBuilder $formBuilder, $competition_id)
    {
      $competition = Competition::find($competition_id);

      $this->authorize('create', ['App\Award', $competition]);

      $form = $formBuilder->create('Award\CreateAwardForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // DB transaction
      $award = DB::transaction(function () use ($data, $competition, $request) {

        // Create the award
        $award = Award::create($data);
        $award->save();

        // Associate award with competition
        $extra = [];

        if($request->input('sponsor'))
        {
          $extra['sponsor'] = $request->input('sponsor');
        }

        $competition->awards()->attach($award->id, $extra);

        return $award;
      });

      $successMessage = "$award->name has been created and added to this competition.";

      if($request->exists('submit_create_another'))
      {
        return redirect()->back()->with('success',$successMessage);
      }
      else {
        return redirect()->route('organizer.competition.show', [$competition_id])->with('success',$successMessage);
      }
    }

    public function manage(FormBuilder $formBuilder, $competition_id)
    {
      $competition = Competition::with(['awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->findOrFail($competition_id);

      $selected_awards = $competition->awards;

      $this->authorize('manage', ['App\Award', $competition]);

      $awards = Award::where('organization_id',    Auth::user()->organization_id)->get();

      $standard_awards = Award::withoutGlobalScope('organization')->where('organization_id', NULL)->get();

      return view('competition_award.organizer.manage', compact('competition', 'awards', 'standard_awards', 'selected_awards'));
    }

    public function update(Request $request, $competition_id)
    {
      $competition = Competition::findOrFail($competition_id);

      $this->authorize('manage', ['App\Award', $competition]);

      $awards = $request->input('awards', []);
      $sponsors = $request->input('sponsors', []);

      $data = [];

      foreach($awards as $award_id)
      {
        $data[$award_id] = ['sponsor' => $sponsors[$award_id]];
      }

      $competition->awards()->sync($data);

      // Set flash data and redirect
      return redirect()->route('organizer.competition.show', [$competition_id]);
    }

    public function assign(FormBuilder $formBuilder, $competition_id)
    {
      $competition =  Competition::with('divisions.choirs.school')->findOrFail($competition_id);
      $awards = $competition->awards;

      $this->authorize('assign', ['App\Award', $competition]);

      return view('competition_award.organizer.assign', compact('competition', 'awards'));
    }

    public function update_assignment(Request $request, $competition_id)
    {
      $competition = Competition::findOrFail($competition_id);
      $this->authorize('assign', ['App\Award', $competition]);

      // Force empty choir_id to be null, not a blank string;
      $awards = $request->input('awards', []);

      $competition->awards()->sync($awards);

      return redirect()->route('organizer.competition.show', [$competition_id])->with('success', 'Award assignments updated.');
    }
}
