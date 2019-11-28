<?php
namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;
use Auth;
use Illuminate\Support\Facades\Storage;

class RecordingController extends Controller
{
    public function postRecording(Request $request)
    {
        $recording = new Recording;
        $recording->judge_id = Auth::user()->person_id;
        $recording->choir_id = $request->choir_id;
        $recording->division_id = $request->division_id;
        $recording->round_id = $request->round_id;
        // upload file
        if($request->file)
        {
            $storagePath = 'recordings/';
            $url = $request->file;
            $storageFileName = uniqid();
            $pathUrl = $storagePath . $storageFileName;    
            $filePath= uploadToS3($pathUrl, $url); 
            $recording->url = $filePath;
        //   $destinationPath = public_path().'/recordings/' ;
        //   $url->move($destinationPath,$storageFileName);
           
        }
        // save modal
        $recording->save();
        // Return success
        return response()->json($recording, 201);
    }


}