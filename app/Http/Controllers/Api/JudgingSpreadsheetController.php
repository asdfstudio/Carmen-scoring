<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Round;
use App\Caption;
use App\Judge;
use App\Comment;
use App\Carmen\Scoreboard;
use App\Events\CommentSaved;

use DB;
use Auth;


class JudgingSpreadsheetController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($round_id)
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

        if ($round->divisions->count() == 0) {
            return redirect()->route('judge.competition.show', [$competition])->with('warning', 'No choirs have been designated for this round yet.');
        }

        $recording_judges = $round->judges;

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
        $backUrl = route('judge.competition.show', [$round->competition->id]);

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

        $choirs = DB::table('choir_round AS cr')
            ->join('choirs AS c', 'c.id', '=', 'cr.choir_id')
            ->join('schools AS s', 's.id', '=', 'c.school_id')
            ->join('divisions AS d', 'cr.round_id', '=', 'd.round_id')
            ->join('rounds AS r', 'r.id', '=', 'd.round_id')
            ->join('choir_division as cd', function($join) {
                $join->on('cd.division_id', '=', 'd.id')->on('cr.choir_id', '=', 'cd.choir_id');
            })
            ->leftJoin('schedule_items AS si', function($join) {
                $join->on('si.division_id', '=', 'cd.division_id')->on('si.choir_id', '=', 'cr.choir_id');
            })
            ->where('cr.round_id', '=', $round_id)
            ->select('c.id', 'cr.performance_order', // 'si.scheduled_time',
                'r.id AS round_id', 'r.name as round_name',
                'd.id as division_id', 'd.name as division_name')
                ->addSelect(DB::raw('CONCAT(s.name, \' \', c.name) AS name'))
                ->addSelect(DB::raw('DATE_FORMAT(si.scheduled_time, \'%l:%i %p\') AS scheduled_time'))
                ->orderBy('cr.performance_order')
            ->get();

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
                'choir_id' => $choir->id,
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir->id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
            if(!$placeholder_comment->exists){
                $placeholder_comment->save();
                event(new CommentSaved($placeholder_comment, $round->competition));
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
        $divisions = $round->divisions;
        // $recordedComments = $recordedComments->first();

        return response()->json(compact('isSpreadsheetScoringActive', 'divisions', 'captions', 'captionWeightingId', 'choirs',
            'criteria', 'scores', 'comments', 'spreadsheetTitle', 'backUrl', 'recordedComments','competition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
