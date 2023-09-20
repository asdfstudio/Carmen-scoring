<?php

namespace App\Http\Controllers\Organizer;

use App\Models\DivisionFile;
use App\Recording;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Auth;

class DivisionFileController extends Controller
{
    public $folder = 'division-files/';


    public function postFile(Request $request)
    {
        $recording = new DivisionFile;
        $recording->uploaded_by = Auth::user()->person_id;
        $recording->choir_id = $request->choir_id;
        $recording->division_id = $request->division_id;
        $recording->round_id = $request->round_id;
        // upload file
        if ($request->file) {
            // Get the file name and relative path
            $storage_path = $this->folder;
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
            $recording->mime = $mime_type;
            $recording->name = $file_to_store->getClientOriginalName();
            // Upload the file to S3 and save the remote path
            $remote_path = uploadToS3($storage_path, $file_to_store, ['ContentType' => $mime_type]);
            $recording->url = $remote_path;
        }
        // Save modal
        $recording->save();

        $recording->formatted_date = $recording->getNiceDate();

        // Return success
        return response()->json($recording, 201);
    }

    public function destroy(Request $request, $id)
    {

        $recording = DivisionFile::findorfail($id);

        $recordingPath = strstr($recording->url, $this->folder);
        removeS3File($recordingPath);
        $recording->delete();

        return $request->session()->flash('success', 'File has been deleted successfully.');

    }
}
