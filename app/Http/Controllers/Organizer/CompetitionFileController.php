<?php

namespace App\Http\Controllers\Organizer;

use App\Recording;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Competition;
use App\Models\DivisionFile;

class CompetitionFileController extends Controller
{
    public function index($competition_id)
    {
        $competition = Competition::with('divisions')->findOrFail($competition_id);

        $divisionIds = $competition->divisions->pluck('id');

        $recordings = Recording::whereIn('division_id', $divisionIds)
            ->with(['round', 'choir', 'judge'])
            ->get();

        $divisionFiles = DivisionFile::whereIn('division_id', $divisionIds)
            ->with(['round', 'choir'])
            ->get();

        // Add a "type" attribute to distinguish file types
        $recordings->each(function ($recording) {
            $recording->type = 'recording';
        });

        $divisionFiles->each(function ($file) {
            $file->type = 'division_file';
        });

        $files = $recordings->merge($divisionFiles);
        $groupedFiles = $files->groupBy(function ($file) {
            return $file->round ? $file->round->id : 'No Round Assigned';
        });

        return view('competition.files.index', compact('competition', 'groupedFiles'));
    }
}
