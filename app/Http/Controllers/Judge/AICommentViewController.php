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

class AICommentViewController extends Controller
{
    public function save(Request $request)
    {
        $judge_id = Auth::user()->person_id;
        $round_id = $request->input('round_id', NULL);
        $choir_id = $request->input('choir_id', NULL);
        $criteria_id = $request->input('criteria_id', NULL);

        $round = Round::with(['competition' => function($query) {
            $query->withoutGlobalScope('organization');
        }])->find($round_id);
        $competition = $round->competition;

        if ($criteria_id) {
            $ai_comment_view = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Criterion',
                'recipient_id' => $criteria_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);

            $comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Criterion',
                'recipient_id' => $criteria_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
        } else {
            $ai_comment_view = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);

            $comment = Comment::firstOrNew([
                'judge_id' => $judge_id,
                'choir_id' => $choir_id,
                'recipient_type' => 'App\Choir',
                'recipient_id' => $choir_id,
                'subject_type' => 'App\Round',
                'subject_id' => $round_id
            ]);
        }

        $comment->ai_comments = $request->input('ai_comment');

        $choir = $ai_comment_view->recipient->name ?? 'Unknown Choir';
        $judge = $ai_comment_view->judge->last_name ?? 'Unknown Judge';

        $round_ID = $ai_comment_view->subject_id ?? 'Unknown Round ID';
        $choir_ID = $ai_comment_view->recipient_id ?? 'Unknown Choir ID';

        if ($choir && $judge) {
            $file_name = "$round_ID-$choir_ID-$choir-$judge.txt";
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google-service-account.json')); 
            $client->addScope(Google_Service_Drive::DRIVE);
            $service = new Google_Service_Drive($client);
            $folder_id = env('GOOGLE_DRIVE_FOLDER_ID');

            try {
                $results = $service->files->listFiles([
                    'q' => "name = '$file_name' and '$folder_id' in parents",
                    'fields' => 'files(id, name, createdTime)',
                ]);

                $files = $results->getFiles();
                if (count($files) > 0) {
                    $file = $files[0];  
                    $file_content = $service->files->get($file->getId(), ['alt' => 'media']);
                    $content = $file_content->getBody()->getContents();

                    // Convert Google Drive createdTime to a readable format
                    $createdTime = $file->getCreatedTime();
                    $formattedDate = date("F j, Y, g:i A", strtotime($createdTime));

                    $messgae = "LLM Generated Summary provided by Judge’s Assistant";

                    // Prepend the uploaded date to the content
                    $ai_comment_view->ai_comments_view = "$messgae\n\nUploaded on: $formattedDate\n\n$content";

                    if (empty($comment->ai_comments)) {
                        $comment->ai_comments = $content;
                        $comment->save();
                    }
                } else {
                    $ai_comment_view->ai_comments_view = "Do not have any AI Summarize";
                }

                return response()->json($ai_comment_view);
            } catch (\Google_Service_Exception $e) {
                return response()->json(['error' => 'Error retrieving file from Google Drive: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'Choir or Judge not found.'], 400);
        }
    }
}
