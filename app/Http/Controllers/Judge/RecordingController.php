<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;
use App\Comment;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google\Auth\Credentials\ServiceAccountCredentials;

class RecordingController extends Controller
{
    public function postRecording(Request $request)
    {
        ini_set('max_execution_time', 600); // 10 minutes
        $recording = new Recording;
        $recording->judge_id = isset($request->judge_id) ? $request->judge_id : Auth::user()->person_id;
        $recording->choir_id = $request->choir_id;
        $recording->division_id = $request->division_id;
        $recording->round_id = $request->round_id;
        // upload file
        if ($request->file) {
            // Get the file name and relative path
            $storage_path = 'recordings/';
            $file_to_store = $request->file;
            $storage_file_name = uniqid();
            $storage_path .= $storage_file_name;

            // Get MIME type
            // require_once 'MIME/Type.php';
            // $mime_type = \MIME_Type::autoDetect($file_to_store);

            // if($mime_type === 'application/octet-stream'){
            //   $mime_type = 'audio/mpeg';
            // }

            $dg_file_name = $file_to_store->getPath() . '/' . $file_to_store->getFilename();
            $mime_type = mime_content_type($dg_file_name);


            // Upload the file to S3 and save the remote path
            $remote_path = uploadToS3($storage_path, $file_to_store, ['ContentType' => $mime_type]);
            $recording->url = $remote_path;

            // Now upload to Google Drive
            $choir = $recording->choir; 
            $judge = $recording->judge;
            $file_name = $request->round_id . '-' . $choir->id . '-' . $choir->name . '-' . $judge->id . '-' . $judge->last_name . '.mp3';

            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google-service-account.json'));
            $client->addScope(Google_Service_Drive::DRIVE_FILE);

            $service = new Google_Service_Drive($client);
            $file_metadata = new Google_Service_Drive_DriveFile([
                'name' => $file_name,
                'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')]
            ]);

            // Upload the file to Google Drive
            $content = file_get_contents($file_to_store->getRealPath());
            $service->files->create($file_metadata, [
                'data' => $content,
                'mimeType' => $mime_type,
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            // // Save the Google Drive file ID in the database
            // $recording->google_drive_file_id = $drive_file->id;
            // $recording->judge_email = $request->judge_email;

            // 3. Send the audio to the transcription API
            $response = Http::timeout(900)->attach('audio', file_get_contents($file_to_store->getRealPath()), $file_to_store->getClientOriginalName())
            ->post('https://audiototext.asdfstudio.com/api/transcribe');
        
        // if ($response->successful()) {
            $responseData = $response->json();
        
        //     \Log::info('Transcription API Response:', $responseData);
        //     var_dump("zzzzzzzzzzzzzzzz", $responseData);
        
            if (isset($responseData['summary'])) {
                $transcribedText = $responseData['summary'];
            } else {
                return response()->json(['error' => 'Transcription API did not return summary', 'response' => $responseData], 500);
            }
            $comment = Comment::firstOrNew([
                'judge_id' => $recording->judge_id,
                'choir_id' => $recording->choir_id,
                'subject_type' => 'App\Round',
                'subject_id' => $recording->round_id
            ]);
        
            $comment->ai_comments = $transcribedText;
            $comment->save();
        
            // return response()->json(['message' => 'Transcription saved', 'ai_comments' => $transcribedText], 201);
        // } else {
        //     // Log the failed response
        //     \Log::error('Transcription API Failed', ['status' => $response->status(), 'body' => $response->body()]);
            
        //     return response()->json([
        //         'error' => 'Transcription failed',
        //         'message' => $response->body()
        //     ], 500);
        // }
        
        // }
        // Save modal
        }
        $recording->save();

        $recording->formatted_date = $recording->getNiceDate();

        // Return success
        return response()->json($recording, 201);
    }
}
