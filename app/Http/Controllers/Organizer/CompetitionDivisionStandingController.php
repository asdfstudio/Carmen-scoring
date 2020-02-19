<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Standing;

use App\Carmen\Ratings;

class CompetitionDivisionStandingController extends Controller
{
    public function ceremony($competition_id, $division_id)
    {
      $division = Division::with(['standings' => function($query) {
        $query->with('choirs')->orderBy('caption_id', 'DESC');
      }, 'awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'awards.choirs' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }])->find($division_id);

      //dd($division);

      return view('competition_division_ceremony.organizer.show', compact('division'));
    }

    public function show($competition_id, $division_id)
    {
      $division = Division::with(['standings','standings.choirs'])->find($division_id);

      return view('competition_division_standing.organizer.show', compact('division'));
    }

    public function edit($competition_id, $division_id, $standing_id)
    {
      $division = Division::find($division_id);

      $standing = $division->standings()->with('choirs')->where('id', $standing_id)->first();

      $this->authorize('update', $standing);

      return view('competition_division_standing.organizer.edit', compact('division', 'standing'));
    }

    public function update($competition_id, $division_id, $standing_id, Request $request)
    {
      $division = Division::find($division_id);

      $standing = $division->standings()->with('choirs')->where('id', $standing_id)->first();

      $this->authorize('update', $standing);

      $choirs = $request->input('choirs');

      $standing->is_consensus_scoring = true;
      $standing->choirs()->sync($choirs);
      $standing->save();

      return redirect()->route('organizer.competition.division.standing.show', [$competition_id, $division_id])->with('success','The division standings have been successfully modified.');
    }
}
