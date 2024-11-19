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
    
        // Fetch recordings
        $recordings = Recording::whereIn('division_id', $divisionIds)
            ->with(['round', 'choir'])
            ->get();
    
        // Fetch division files
        $divisionFiles = DivisionFile::whereIn('division_id', $divisionIds)
            ->with(['round', 'choir'])
            ->get();
    
        // Combine and group files by round
        $files = $recordings->merge($divisionFiles);
        $groupedFiles = $files->groupBy(function ($file) {
            return $file->round ? $file->round->id : 'No Round Assigned';
        });
    
        return view('competition.files.index', compact('competition', 'groupedFiles'));
    }
    
    
}
