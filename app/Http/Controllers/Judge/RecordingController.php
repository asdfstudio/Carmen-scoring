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
        $recording->judge_id = isset($request->judge_id)?$request->judge_id:Auth::user()->person_id;
        $recording->choir_id = $request->choir_id;
        $recording->division_id = $request->division_id;
        $recording->round_id = $request->round_id;
        // upload file
        if($request->file)
        {
            // Get the file name and relative path
            $storage_path = 'recordings/';
            $file_to_store = $request->file;
            $storage_file_name = uniqid();
            $storage_path .= $storage_file_name;

            // Get MIME type
            require_once 'MIME/Type.php';
            $mime_type = \MIME_Type::autoDetect($file_to_store);
            if($mime_type === 'application/octet-stream'){
              $mime_type = 'audio/mpeg';
            }

            // Upload the file to S3 and save the remote path
            $remote_path = uploadToS3($storage_path, $file_to_store, ['ContentType' => $mime_type]);
            $recording->url = $remote_path;
        }
        // Save modal
        $recording->save();

        // Return success
        return response()->json($recording, 201);
    }


}
