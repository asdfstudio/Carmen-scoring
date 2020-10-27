<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;
use App\Competition;
use App\Choir;
use App\Round;
use App\RawScore;
use App\Caption;
use App\Judge;
use App\Comment;
use App\Recording;
use App\Carmen\Scorekeeper;
use App\Carmen\Scoreboard;
use App\Events\CommentSaved;

use DB;
use Auth;

class CompetitionDivisionRoundController extends Controller
{


    public function summary($competition_id, $division_id, $round_id)
    {

        $judge_id = Auth::user()->person_id;

        $rawScores = RawScore::with('judge','choir')
            ->where('division_id',$division_id)
            ->where('round_id',$round_id)
            ->where('judge_id',$judge_id)
            ->get();

        $scoreboard = new Scoreboard(['division_id' => $division_id, 'round_id' => $round_id]);

        $rawScores = $scoreboard->rawScores;
        $weightedScores = $scoreboard->weightedScores;

        $division = Division::with('round')->find($division_id);
        $round_id = $division->round_id;

        $division = $division->load(['choirs', 'choirs.recordings', 'round.judges' => function($query) {
            $query->groupBy('judge_id');
        },'round.judges.captions' => function($query) use ($round_id) {
            $query->where('round_id',$round_id);
        }, 'round.judges.captions.criteria',
            'competition' => function($query) {
                $query->withoutGlobalScope('organization');
            },'competition.organization'])->find($division_id);

        $captions = Caption::forSheet($division->sheet);
        $competition = $division->competition;
        $round = $division->round;
        // $round = Round::with(['divisions','division.competition' => function($query) {
        //   $query->withoutGlobalScope('organization');
        // },'round.judges' => function($query) use ($judge_id) {
        //     $query->where('judge_id',$judge_id)->first();
        //   }, 'round.judges.captions' => function($query) use ($division_id) {
        //     $query->where('division_id',$division_id);
        //   },
        //    'round.judges.captions.criteria','choirs',
        //    'choirs.recordings' => function($query) use ($division_id, $judge_id) {
        //     $query->where('division_id', $division_id)->where('judge_id', $judge_id);
        //   }])->find($round_id);


        $captions = Caption::forSheet($division->sheet);
        return view('competition_round.judge.summary',compact('rawScores', 'weightedScores', 'captions', 'round', 'competition', 'division'));
    }

    public function spreadsheet($competition_id, $round_id)
    {
        $judge_id = Auth::user()->person_id;

        $round = Round::with(['competition', 'competition.organization', 'sheet',
            'divisions', 'divisions.choirs', 'divisions.choirs.recordings',
            'judges' => function($query) {
                $query->groupBy('judge_id');
            },'judges.captions' => function($query) use ($round_id) {
                $query->where('round_id',$round_id);
            }, 'judges.captions.criteria'
            ])->find($round_id);

        $competition = $round->competition;

        $recording_judges = $round->judges;

        // TODO: Allocate these properly to divisions
        $rating_system = $round->divisions()->first()->rating_system;

        $judge = Judge::with(['captions' => function($query) use ($round_id, $round) {
            $query->where('round_id', $round_id);
        }])->find($judge_id);

        $judgeCaptionIds = $judge->captions->pluck('id')->toArray();
        $captions = Caption::forSheet($round->sheet);
        $captions = $captions->whereIn('id', $judgeCaptionIds);

        $criteria = $round->sheet->criteria->whereIn('caption_id', $judgeCaptionIds);

        //$before = memory_get_usage();
        $scoreboard = new Scoreboard(['round_id' => $round_id, 'judge_id' => $judge_id]);
        //$after = memory_get_usage();
        //$allocatedSize = ($after - $before);
        //dd($allocatedSize/1024/1024);

        $spreadsheetTitle = $round->name;
        $backUrl = route('judge.competition.show', [$competition_id]);

        $isSpreadsheetScoringActive = $round->status;

        $captionWeightingId = $round->caption_weighting_id;

        // Convert to arrays for use with new Vue spreadsheet
        $captions = $captions->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'color_id' => $item->color_id
            ];
        })->toArray();
        $captions = array_values($captions);

        $divisions = $round->divisions->pluck('id', 'name');

        $choirs = [];
        foreach ($round->divisions as $division) {
            foreach ($division->choirs as $choir) {
                $choirs[] = [
                    'id' => $choir->id,
                    'name' => $choir->full_name,
                    'round_id' => $round_id,
                    'division_id' => $division->id,
                    'division_name' => $division->name,
                    'performance_order' => $choir->pivot->performance_order
                ];
            }
        }

        $criteria = $criteria->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'caption_id' => $item->caption_id,
                'name' => $item->name,
                'description' => $item->description,
                'minScore' => 0,
                'maxScore' => $item->max_score,
                'increment' => 0.5 // needs set
            ];
        })->values();

        $scores = $scoreboard->rawScores->map(function ($item, $key) {
            return [
                'choir_id' => $item->choir_id,
                'criterion_id' => $item->criterion_id,
                'caption_id' => $item->criterion_caption_id,
                'raw_score' => floatval($item->score)
            ];
        })->toArray();

        // Make sure there is at least a placeholder comment for this judge targeting each choir.
        foreach($choirs as $choir){
            $placeholder_comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir['id'],
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir['id'],
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
            if(!$placeholder_comment->exists){
                $placeholder_comment->save();
                event(new CommentSaved($placeholder_comment, $division->competition));
            }
        }

        $round->refresh();

        $comments = $round->feedback->where('judge_id', $judge_id)->map(function ($item, $key) {
            return [
                'choir_id' => $item->choir_id,
                'comment' => $item->comments
            ];
        })->toArray();
        $recordedComments = $recording_judges->map(function ($item, $key) {
            return $item->recordings;
        });

        // JSON encode
        $choirs = json_encode($choirs);
        $divisions = json_encode($round->divisions);
        $criteria = json_encode($criteria);
        $comments = json_encode($comments);
        $recordedComments = json_encode($recordedComments->first());
        $scores = json_encode($scores);
        $captions = json_encode($captions);
        $rating_system = json_encode($rating_system);
        $competition = json_encode($competition);

        return view('judge.spreadsheet', compact('isSpreadsheetScoringActive', 'divisions', 'captions', 'captionWeightingId', 'choirs',
            'criteria', 'scores', 'comments', 'spreadsheetTitle', 'backUrl', 'rating_system','recordedComments','competition'));
    }

    public function spreadsheet_sources($competition_id, $division_id, $round_id)
    {
        $judge_id = Auth::user()->person_id;

        //$competition = Competition::find($competition_id);
        //$division = Division::find($division_id);

        $round = Round::with(['division', 'division.competition' => function($query) {
            $query->withoutGlobalScope('organization');
        }, 'division.judges' => function($query) use ($judge_id) {
            $query->where('judge_id',$judge_id)->first();
        },'division.judges.captions' => function($query) use ($division_id) {
            $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria','choirs','division.rounds', 'sources'])->find($round_id);

        $division = $round->division;
        $competition = $division->competition->organization;
        $rating_system = $division->rating_system;

        $captionWeightingId = $division->caption_weighting_id;

        $judge = $round->judges->first();

        $judge = Judge::with(['captions' => function($query) use ($round_id) {
            $query->where('round_id', $round_id);
        }])->find($judge_id);

        //
        $source_division_ids = $round->sources->pluck('division_id')->toArray();

        $source_divisions = Division::with('choirs', 'judges')->whereIn('id', $source_division_ids)->get();

        $source_choirs = collect();

        $source_divisions->each(function($item, $key) use ($source_choirs) {
            if($item->has('choirs'))
            {
                $item->choirs->each(function($choir,$key) use ($source_choirs) {
                    return $source_choirs->push($choir);
                });
            }
        });

        $choirs = $source_choirs;

        $source_ids = $round->sources->pluck('id')->toArray();
        $scoreboard = new Scoreboard(['round_id' => $source_ids, 'judge_id' => $judge_id]);

        $judgeCaptionIds = $judge->captions->pluck('id')->toArray();
        $captions = Caption::forSheet($division->sheet);
        $captions = $captions->whereIn('id', $judgeCaptionIds);

        $criteria = $division->sheet->criteria->whereIn('caption_id', $judgeCaptionIds);

        // $isScoringActive = false;
        $isSpreadsheetScoringActive = false;

        foreach ($round->sources as $source) {
            if ($source->status == 'Active') {
                // $isScoringActive = true;
                $isSpreadsheetScoringActive = 'Active';
                continue;
            }
        }

        //dd($scoreboard);

        //$rawScores = $scoreboard->rawScores;
        //$weightedScores = $scoreboard->weightedScores;
        //$rankedScores = $scoreboard->rankedScoresForCurrentMethod;

        $spreadsheetTitle = $division->name . ' > Source Rounds';
        $backUrl = route('judge.competition.division.show', [$competition_id,$division_id]);
        //$isSpreadsheetScoringActive = $round->status;

        // Convert to arrays for use with new Vue spreadsheet
        $captions = $captions->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'color_id' => $item->color_id
            ];
        })->toArray();


        $divisions = $source_divisions->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        });


        // dd($source_divisions);

        $choirs = $choirs->map(function ($item, $key) use ($round) {

            $sources = $round->sources;
            $match = $sources->where('division_id', $item->pivot->division_id)->first();

            if ($match) {
                $round_id = $match->pivot->source_round_id;
            } else {
                $round_id = null;
            }

            return [
                'id' => $item->id,
                'name' => $item->full_name,
                'round_id' => $round_id,
                'division_id' => $item->pivot->division_id
            ];
        })->toArray();


        $criteria = $criteria->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'caption_id' => $item->caption_id,
                'name' => $item->name,
                'description' => $item->description,
                'minScore' => 0,
                'maxScore' => $item->max_score,
                'increment' => 0.5 // needs set
            ];
        })->values();



        $scores = $scoreboard->rawScores->map(function ($item, $key) {
            return [
                'choir_id' => $item->choir_id,
                'criterion_id' => $item->criterion_id,
                'caption_id' => $item->criterion_caption_id,
                'raw_score' => floatval($item->score)
            ];
        })->toArray();

        //dd($scores);


        // Make sure there is at least a placeholder comment for this judge targeting each choir.
        foreach($choirs as $choir){
            $placeholder_comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir['id'],
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir['id'],
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
            if(!$placeholder_comment->exists){
                $placeholder_comment->save();
                event(new CommentSaved($placeholder_comment, $division->competition));
            }
        }

        $feedback = Comment::where('judge_id', $judge_id)
            ->whereIn('choir_id', $source_choirs->pluck('id')->toArray())
            ->where('subject_type', 'App\Round')
            ->where('recipient_type', 'App\Choir')
            ->whereIn('subject_id', $round->sources->pluck('id')->toArray())
            ->get();

        //dd($feedback);

        $comments = $feedback->map(function ($item, $key) {
            return [
                'choir_id' => $item->choir_id,
                'comment' => $item->comments
            ];
        })->toArray();

        $recordings = Recording::all()->where('judge_id', $judge_id)->whereIn('round_id', array_merge([$round_id], $source_ids))->whereIn('division_id', array_merge([$division_id], $source_division_ids));
        $recordedComments = array_values($recordings->toArray());
        //dd($recordedComments);

        // JSON encode
        $choirs = json_encode($choirs);
        $divisions = json_encode($divisions);
        $criteria = json_encode($criteria);
        $comments = json_encode($comments);
        $scores = json_encode($scores);
        $captions = json_encode($captions);
        $recordedComments = json_encode($recordedComments);
        $rating_system = json_encode($rating_system);
        $competition = json_encode($competition);
        return view('judge.spreadsheet', compact('isSpreadsheetScoringActive', 'captions', 'divisions', 'captionWeightingId', 'choirs', 'criteria', 'scores', 'rating_system', 'comments', 'spreadsheetTitle', 'backUrl','competition', 'recordedComments'));
    }


    public function details($competition,$division_id,$round_id)
    {
        $judge_id = Auth::user()->person_id;

        $rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

        $round = Round::with(['division','division.competition' => function($query) {
            $query->withoutGlobalScope('organization');
        },'division.judges' => function($query) use ($judge_id) {
            $query->where('judge_id',$judge_id)->first();
        }, 'division.judges.captions' => function($query) use ($division_id) {
            $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria'])->find($round_id);


        $captions = Caption::forSheet($round->division->sheet);

        return view('competition_division_round.judge.show',compact('rawScores','captions','round', 'captions'));
    }

    public function save_scores($competition_id, $division_id, $round_id, Request $request)
    {
        $judge_id = Auth::user()->person_id;

        $scorekeeper = new Scorekeeper([
            'division_id' => $division_id,
            'round_id' => $round_id,
            'judge_id' => $judge_id
        ]);

        $choirScores = $request->input('scores', NULL);

        // Save multiple scores
        if($choirScores)
        {
            $response = $scorekeeper->save_multiple_choirs_scores($choirScores);

            return $response;
        }
    }
}
