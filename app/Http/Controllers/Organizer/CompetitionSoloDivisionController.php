<?php

namespace App\Http\Controllers\Organizer;

use Event;
use App\Choir;
use App\Judge;
use App\Caption;
use App\Performer;
use App\Competition;
use App\SoloDivision;
use App\SoloRawScore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Carmen\SoloTotalScores;
use App\Carmen\SoloRankedScores;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Events\SoloDivisionScoringFinalized;

class CompetitionSoloDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Competition $competition, Request $request, FormBuilder $formBuilder)
    {
        $judges = Judge::get()->pluck('full_name', 'id')->toArray();
        $form = $formBuilder->create('SoloDivision\CreateForm', [
          'url' => route('organizer.competition.solo-division.store', $competition),
          'data' => [
            'judges' => $judges
          ]
        ]);

        return view('solo-division.organizer.create', compact('competition', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Competition $competition, Request $request, FormBuilder $formBuilder)
    {
        $soloDivision = new SoloDivision();
        $soloDivision->competition()->associate($competition);
        $soloDivision->fill($request->input());
        $soloDivision->save();
        $soloDivision->judges()->sync($request->input('judge_id'));

        return redirect()->route('organizer.competition.solo-division.show', [$competition, $soloDivision])->with('success', 'Your solo division has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition, $id, FormBuilder $formBuilder)
    {
        $soloDivision = SoloDivision::with('performers')->find($id);

        $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)->get();

        $totalScores = (new SoloTotalScores($rawScores , $soloDivision->performers))->get();
        $rankedScores = (new SoloRankedScores($totalScores , $soloDivision->performers))->get();

        $maleRank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('gender', 'M')))->get();
        $femaleRank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('gender', 'F')))->get();

        $soloDivision->performers->transform(function($performer, $key) use ($rawScores, $rankedScores, $maleRank, $femaleRank) {
          $performer->rank = $rankedScores->where('performer_id', $performer->id)->pluck('rank')->first();
          $performer->score = $rawScores->where('performer_id', $performer->id)->sum('score');

          if ($performer->gender == 'M') {
            $genderRank = $maleRank;
          } elseif ($performer->gender == 'F') {
            $genderRank = $femaleRank;
          } else {
            $genderRank = null;
          }

          if ($genderRank) {
            $performer->gender_rank = $genderRank->where('performer_id', $performer->id)->pluck('rank')->first();
          }


          return $performer;
        });

        //
        $activateScoringForm = $formBuilder->create('Scoring\ActivateScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.solo-division.update-status', [$competition, $soloDivision])
        ]);

        $completeScoringForm = $formBuilder->create('Scoring\CompleteScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.solo-division.update-status', [$competition, $soloDivision])
        ]);

        $finalizeScoringForm = $formBuilder->create('Scoring\FinalizeScoringForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.solo-division.update-status', [$competition, $soloDivision])
        ]);

        return view('solo-division.organizer.show', compact('competition', 'soloDivision', 'activateScoringForm', 'completeScoringForm', 'finalizeScoringForm'));
    }

    /**
     * [resultsFemale description]
     * @param  Competition $competition [description]
     * @param  [type]      $id          [description]
     * @return [type]                   [description]
     */
    public function resultsFemale(Competition $competition, $id)
    {
      return $this->results($competition, $id, 'F');
    }

    /**
     * [resultsMale description]
     * @param  Competition $competition [description]
     * @param  [type]      $id          [description]
     * @return [type]                   [description]
     */
    public function resultsMale(Competition $competition, $id)
    {
      return $this->results($competition, $id, 'M');
    }

    /**
     * [results description]
     * @param  Competition $competition [description]
     * @param  [type]      $id          [description]
     * @param  [type]      $gender      [description]
     * @return [type]                   [description]
     */
    public function results(Competition $competition, $id, $gender = null)
    {
        $soloDivision = SoloDivision::with('performers', 'judges')->find($id);

        if ($gender) {
          $genderName = $gender == 'M' ? 'Male' : 'Female';
          $soloDivision->performers = $soloDivision->performers->where('gender', $gender);
        } else {
          $genderName = null;
        }

        $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)->get();

        $totalScores = (new SoloTotalScores($rawScores , $soloDivision->performers))->get();
        $rankedScores = (new SoloRankedScores($totalScores , $soloDivision->performers))->get();

        if (!$gender) {
          $maleRank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('gender', 'M')))->get();
          $femaleRank = (new SoloRankedScores($totalScores, $soloDivision->performers->where('gender', 'F')))->get();
        } else {
          $maleRank = null;
          $femaleRank = null;
        }

        $judges = $soloDivision->judges;

        $soloDivision->performers->transform(function($performer, $key) use ($rawScores, $rankedScores, $maleRank, $femaleRank, $judges) {
          $performer->rank = $rankedScores->where('performer_id', $performer->id)->pluck('rank')->first();
          $performer->score = $rawScores->where('performer_id', $performer->id)->sum('score');

          if ($performer->gender == 'M') {
            $genderRank = $maleRank;
          } elseif ($performer->gender == 'F') {
            $genderRank = $femaleRank;
          } else {
            $genderRank = false;
          }

          if ($genderRank) {
            $performer->gender_rank = $genderRank->where('performer_id', $performer->id)->pluck('rank')->first();
          } else {
            $performer->gender_rank = false;
          }

          $judgeScores = [];

          foreach ($judges as $judge) {
            $judgeScores[$judge->id] = $rawScores->where('performer_id', $performer->id)->where('judge_id', $judge->id)->sum('score');
          }

          $performer->judgeScores = $judgeScores;

          return $performer;
        });

        $soloDivision->performers = $soloDivision->performers->sortBy('rank');

        return view('solo-division.organizer.results', compact('competition', 'soloDivision', 'genderName'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Competition $competition, Request $request, FormBuilder $formBuilder, $id)
    {
      $soloDivision = SoloDivision::find($id);
      $judges = Judge::get()->pluck('full_name', 'id')->toArray();

      $form = $formBuilder->create('SoloDivision\CreateForm', [
        'url' => route('organizer.competition.solo-division.update', [$competition, $id]),
        'method' => 'PATCH',
        'model' => $soloDivision,
        'data' => [
          'judges' => $judges
        ]
      ]);

      return view('solo-division.organizer.edit', compact('competition', 'form', 'soloDivision'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Competition $competition, Request $request, FormBuilder $formBuilder, $id)
    {
        $soloDivision = SoloDivision::find($id);
        $soloDivision->fill($request->input());
        $soloDivision->save();
        $soloDivision->judges()->sync($request->input('judge_id'));

        return redirect()->route('organizer.competition.solo-division.show', [$competition, $soloDivision])->with('success', 'Your solo division has been updated.');
    }

    public function manage(Competition $competition, FormBuilder $formBuilder, $id)
    {
        $choirs = Choir::get()->pluck('full_name', 'id')->toArray();;
        $soloDivision = SoloDivision::with(['performers'])->find($id);

        return view('solo-division.organizer.manage', compact('competition', 'soloDivision', 'choirs'));
    }


    public function manageStore(Competition $competition, Request $request, $id)
    {
        //dd($request->input('performer'));
        $soloDivision = SoloDivision::find($id);

        $performersData = $request->input('performer');

        //dd($performersData);

        foreach ($performersData as $performerData) {

          if(!$performerData['id'] AND !$performerData['choir_id']) continue;

          $performer = Performer::firstOrNew(['id' => $performerData['id']]);

          if ($performerData['id'] AND !$performerData['choir_id']) {
            //Destroy
            $performer->delete();
          } else {
            // Fill with data and save
            $performer->fill($performerData);
            $performer->soloDivision()->associate($soloDivision);
            $performer->save();
          }


        }

        return redirect()->route('organizer.competition.solo-division.manage', [$competition, $id])->with('success', 'Performers saved!');
    }


    public function showPerformer(Competition $competition, SoloDivision $soloDivision, Performer $performer)
    {
        $soloDivision->load('sheet', 'judges');
        $captionsIds = $soloDivision->sheet->caption_ids;
        $captions = Caption::whereIn('id', $captionsIds)->get();

        $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)
                                      ->where('performer_id', $performer->id)
                                      ->get();

        return view('solo-division.organizer.show-performer', compact('competition', 'soloDivision', 'performer', 'captions', 'rawScores'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function updateStatus(Competition $competition, SoloDivision $soloDivision, Request $request)
    {
      if ($request->input('activate')) {

        $soloDivision->activateScoring();

      } elseif ($request->input('complete')) {

        $soloDivision->completeScoring();

      } elseif($request->input('finalize')) {

        $soloDivision->finalizeScoring();

        Event::fire(new SoloDivisionScoringFinalized($soloDivision));

      }

      return redirect()->route('organizer.competition.solo-division.show', [$competition, $soloDivision]);
    }
}
