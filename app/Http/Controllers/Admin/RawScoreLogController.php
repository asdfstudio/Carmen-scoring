<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RawScoreLogController extends Controller
{
    public function index()
    {
      $allFiles = Storage::disk('raw-score-logs')->files();
      $rawScoreFiles = collect();

      foreach ($allFiles as $key => $filename) {
        if(substr($filename, 0, 10) != 'raw-scores') continue;

        $item = [
          'date' => substr($filename, 11, 10),
          'filename' => $filename
        ];

        $rawScoreFiles->push($item);
      }

      return view('raw_score_log.admin.index', compact('rawScoreFiles'));
    }


    public function show($date)
    {
      $filename = 'raw-scores-'.$date.'.log';
      $exists = Storage::disk('raw-score-logs')->exists($filename);

      if ($exists) {
        $fileContents = Storage::disk('raw-score-logs')->get($filename);
      }

      return view('raw_score_log.admin.show', compact('date', 'fileContents'));
    }
}
