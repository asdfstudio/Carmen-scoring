<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Standing;

class CompetitionDivisionStandingController extends Controller
{
    public function show($competition_id, $division_id)
    {
      $division = Division::with(['standing','standing.choirs'])->find($division_id);

      return view('competition_division_standing.organizer.show', compact('division'));
    }

    public function edit($competition_id, $division_id)
    {
      $division = Division::with('standing','standing.choirs')->find($division_id);

      $this->authorize('update', $division->standing);

      return view('competition_division_standing.organizer.edit', compact('division'));
    }

    public function update($competition_id, $division_id, Request $request)
    {
      $division = Division::with('standing','standing.choirs')->find($division_id);

      $this->authorize('update', $division->standing);

      $choirs = $request->input('choirs');

      $division->standing->is_consensus_scoring = true;
      $division->standing->choirs()->sync($choirs);
      $division->standing->save();

      return redirect()->route('organizer.competition.division.standing.show', [$competition_id, $division_id])->with('success','The division standings have been successfully modified.');
    }
}
