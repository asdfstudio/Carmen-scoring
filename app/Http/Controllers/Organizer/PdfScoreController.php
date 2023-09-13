<?php

namespace App\Http\Controllers\Organizer;

use App\Caption;
use App\Carmen\Ratings;
use App\Carmen\Scoreboard;
use App\Division;
use App\Round;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


use Auth;
use Response;
use PDF;
use Spatie\Browsershot\Browsershot;
class PdfScoreController extends Controller
{
    public function downloadForRound(Request $request)
    {
        $typePdf = $request->input('type_pdf', 'raw');
        $round_id = $request->input('round_id');
        $round = Round::with([
            'competition',
            'divisions',
            'divisions.choirs',
            'judges' => function($query) {
                $query->groupBy('judge_id');
            },
            'judges.captions' => function($query) use ($round_id) {
                $query->where('round_id',$round_id);
            },
            'judges.captions.criteria'
        ])->find($round_id);

        $competition = $round->competition;
        $choirs = collect([]);
        foreach ($round->divisions as $division) {
            $choirs = $choirs->concat($division->choirs);
        }

        $judges = $round->judges;
        $captions = Caption::forSheet($round->sheet);

        $scoreboard = new Scoreboard(['round_id' => $round_id]);

        $rawScores = $scoreboard->extendedRawScores;
        $weightedScores = $scoreboard->extendedRawScores;
        $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

        if ($division->round->scoring_method_id === 3 || $division->round->scoring_method_id === 4) {
            $show_borda = true;
        } else {
            $show_borda = false;
        }
        $kindScore = '';
        if ($typePdf === 'raw') {
            $kindScore = 'Raw';
        } elseif ($typePdf == 'weighted') {
            $kindScore = "Weighted (division scoring method, {$division->round->captionWeighting->name})";
        } elseif ($typePdf === 'condorcet') {
            $kindScore = "Condorcet (division scoring method)";
        } elseif ($typePdf === 'rank') {
            if ($show_borda) {
                $kindScore = 'Borda Count';
            } else {
                $kindScore = 'Rankings';
            }
        } elseif ($typePdf === 'average') {
            $kindScore = 'Average';
        }

        $html = view('pdf_score.organizer.round_score',compact('rawScores', 'weightedScores', 'rankedScores',
        'round', 'competition', 'scoreboard', 'judges', 'choirs', 'captions',
        'typePdf','kindScore'
        ))->render();
        $fileName = "round_score_" . $typePdf. '_' . Carbon::now() .'.pdf';
         Browsershot::html($html)->format('letter')->setOption('landscape', true)
             ->noSandbox()
             ->emulateMedia("screen")
            ->save(storage_path('app/public').'/'.$fileName);
        return \Storage::disk('public')->download($fileName);

//        return Response::make(file_get_contents(storage_path('app/public').'/'.$fileName), 200, [
//            'Content-Type' => 'application/pdf',
//            'Content-Disposition' => 'inline; filename="'.$fileName.'"'
//        ]);

    }

    public function download(Request $request)
    {
        $competition_id = $request->input('competition_id');
        $division_id = $request->input('division_id');
        $division = Division::with(['choirs', 'round', 'round.sheet', 'round.competition', 'round.judges' => function ($query) {
            $query->groupBy('judge_id');
        }])->find($division_id);

        $round = $division->round;
        $competition = $round->competition;
        $captions = Caption::forSheet($division->round->sheet);
        $judges = $round->judges;
        $choirs = $division->choirs;
        $caption_ids = $round->sheet->caption_ids;
        $captions = Caption::forSheet($division->round->sheet);
        $ratings = (new Ratings($division))->all();

        $scoreboard = new Scoreboard(['division_id' => $division_id]);
        $rawScores = $scoreboard->extendedRawScores;
        $weightedScores = $scoreboard->extendedRawScores;
        $rankedScores = $scoreboard->rankedScoresForCurrentMethod;
        if ($division->round->scoring_method_id === 3 || $division->round->scoring_method_id === 4) {
            $show_borda = true;
        } else {
            $show_borda = false;
        }
        $typePdf = $request->input('type_pdf', 'raw');
        $kindScore = '';
        if ($typePdf === 'raw') {
            $kindScore = 'Raw';
        } elseif ($typePdf == 'weighted') {
            $kindScore = "Weighted (division scoring method, {$division->round->captionWeighting->name})";
        } elseif ($typePdf === 'condorcet') {
            $kindScore = "Condorcet (division scoring method)";
        } elseif ($typePdf === 'rank') {
            if ($show_borda) {
                $kindScore = 'Borda Count';
            } else {
                $kindScore = 'Rankings';
            }
        } elseif ($typePdf === 'average') {
            $kindScore = 'Average';
        }
        $html = view('pdf_score.organizer.division_score', compact('round', 'competition', 'division', 'judges', 'choirs',
            'captions', 'scoreboard', 'rawScores', 'weightedScores', 'rankedScores',
            'typePdf', 'kindScore'
        ))->render();

    }
}
