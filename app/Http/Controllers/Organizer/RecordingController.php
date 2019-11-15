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

    public function destroy(Request $request,$id)
    {
       
        $recording=Recording::findorfail($id); // fetch the recording
      //  $recordingPath = strstr($recording->url, 'recordings/'); remove from s3
      //  removeS3File($recordingPath);
        $recording->delete(); //delete the fetched recording
       
       return  $request->session()->flash('success', 'Record has been deleted successfully.');
        
    }
}