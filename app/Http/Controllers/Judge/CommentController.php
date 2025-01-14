<?php

namespace App\Http\Controllers\Judge;

use Auth;
use App\Round;
use App\Comment;
use App\Recording;
use App\Http\Requests;
use App\Events\CommentSaved;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google\Auth\Credentials\ServiceAccountCredentials;

class CommentController extends Controller
{
    public function save(Request $request)
    {
        $judge_id = Auth::user()->person_id;
        $round_id = $request->input('round_id', NULL);
        $choir_id = $request->input('choir_id', NULL);
        $criteria_id = $request->input('criteria_id', NULL);
    
        // Find the round and competition
        $round = Round::with(['competition' => function($query) {
            $query->withoutGlobalScope('organization');
        }])->find($round_id);
        $competition = $round->competition;
    
        // Check if it's a comment for a criterion or for the choir
        if ($criteria_id) {
            // Save comment for criterion
            $comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Criterion',
                'recipient_id' => $criteria_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
        } else {
            // Save comment for choir
            $comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
        }
    
        // Ensure choir and judge are not null (You can replace these with actual data as required)
        // $choir = "Test Ensemble";
        // $judge = "Judge";

        $choir = $comment->recipient->name ?? 'Unknown Choir';
        $judge = $comment->judge->last_name ?? 'Unknown Judge';

        if ($choir && $judge) {
            // Construct the file name using choir and judge details
            $file_name = "$choir-$judge.txt";  // Name of the .txt file
    
            // Now retrieve the file content from Google Drive
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google-service-account.json')); // Path to your service account file
            $client->addScope(Google_Service_Drive::DRIVE);
    
            $service = new Google_Service_Drive($client);
            $folder_id = env('GOOGLE_DRIVE_FOLDER_ID'); // Folder ID from .env file
    
            try {
                // Search for the .txt file in the Google Drive folder by file name
                $results = $service->files->listFiles([  // Use listFiles instead of list
                    'q' => "name = '$file_name' and '$folder_id' in parents",
                    'fields' => 'files(id, name)',
                ]);
                
                $files = $results->getFiles();

                // Check if file exists
                if (count($files) > 0) {
                    $file = $files[0];  // Take the first file (assuming it's the correct one)

                    // Retrieve the content of the .txt file
                    $file_content = $service->files->get($file->getId(), ['alt' => 'media']);
                    $content = $file_content->getBody()->getContents(); // Retrieve the file content
    
                    // Save the text content into the comment
                    $comment->comments = $content;
                } else {
                    // If file not found, fallback to the request input comment
                    $comment->comments = $request->input('comment');
                }

                // Save the comment to the database
                $comment->save();

                // Trigger the CommentSaved event
                event(new CommentSaved($comment, $competition));

                return response()->json($comment);

            } catch (\Google_Service_Exception $e) {
                // Error while fetching file
                return response()->json(['error' => 'Error retrieving file from Google Drive: ' . $e->getMessage()], 500);
            }

        } else {
            // Handle case where choir or judge is null
            return response()->json(['error' => 'Choir or Judge not found.'], 400);
        }
    }
}
