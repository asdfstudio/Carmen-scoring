<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;
use Auth;
use Illuminate\Support\Facades\Storage;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google\Auth\Credentials\ServiceAccountCredentials;

class RecordingController extends Controller
{
    public function postRecording(Request $request)
    {
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
            $file_name = $choir->name . '-' . $judge->last_name;

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
        }
        // Save modal
        $recording->save();

        $recording->formatted_date = $recording->getNiceDate();

        // Return success
        return response()->json($recording, 201);
    }
}
