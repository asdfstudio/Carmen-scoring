<?php
namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;
use Auth;


class RecordingController extends Controller
{

    public function getJudgeRecording(Request $request)
    {
        $data=$request->all();
        unset($data['_token']);
        return Recording::where($data)->get();
        // Get an existing recording
    }

    public function destroy(Request $request, $id)
    {

        $recording = Recording::findorfail($id);

        $recordingPath = strstr($recording->url, 'recordings/');
        removeS3File($recordingPath);
        $recording->delete();

        return  $request->session()->flash('success', 'Record has been deleted successfully.');

    }
}
