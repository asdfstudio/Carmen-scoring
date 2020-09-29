<?php

namespace App\Http\Controllers\Organizer;

use App\Audience;
use App\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Choir;
use App\School;
use App\Place;
use App\Director;
use App\Person;

use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilder;

use Event;
use App\Events\DivisionChoirCreated;
use App\Events\DivisionChoirRemoved;

class CompetitionDivisionAudienceController extends Controller
{

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder, $competition_id,$division_id)
    {
        $audience =Audience::where('division_id',$division_id)->first();
        $division = Division::with('competition','choirs', 'choirs.directors')->find($division_id);
        $organization_slug = $this->getOrganizationSlug($division->competition->organization);
				return view('competition_division_audience_vote.organizer.index',compact('division','competition_id','division_id','organization_slug','audience'));
    }

    public function store(Request $request){
        $data = $request->all();
        $audience =Audience::where('division_id',$data['division_id'])->first();
        if($audience){
            $audience->alias_name = $data['alias_name'];
            $audience->is_dark = $data['is_dark'];
            $audience->banner_type = $data['banner_type'];
            $audience->banner_upload = $data['banner_upload'];
            $audience->social = $data['social'];
            $audience->list_of_votes = isset($data['list_of_votes'])?$data['list_of_votes']:[];
            $audience->banner_embed = $data['banner_embed'];
            $audience->limit_result = $data['limit_result'];
            $audience->is_premium_vote = $data['is_premium_vote'];
            $audience->is_required_login = isset($data['is_required_login'])?1:0;
            $audience->disable_vote = isset($data['disable_vote'])?1:0;
            $audience->save();
        }else{
            $data['created_at'] = date("Y-m-d H:i:s");
            if(!isset($data['is_required_login']))$data['is_required_login'] = 0;
            if(!isset($data['disable_vote']))$data['disable_vote'] = 0;
            if(!isset($data['is_free_vote']))$data['is_free_vote'] = 0;

             Audience::create($data);
        }

        return redirect(route('organizer.competition.division.audience.index',[$data['competition_id'],$data['division_id']]));
    }

    private function getOrganizationSlug($organization){
        $ary = explode(' ',trim($organization->name));
        return strtolower($ary[0]);
    }

    /*
  File upload
  */
    public function fileupload(Request $request){
      $fileName = '';
      if($request->hasFile('file')) {
          // Upload path
          $destinationPath = 'uploads';

          // Create directory if not exists
          if (!file_exists($destinationPath)) {
              mkdir($destinationPath, 0755, true);
          }

          // Get file extension
          $extension = $request->file('file')->getClientOriginalExtension();

          // Valid extensions
          $validextensions = array("jpeg","jpg","png","mp4");

          // Check extension
          if(in_array(strtolower($extension), $validextensions)){
             if (env('AWS_ACCESS_KEY_ID')) {

               if (strtolower($extension) === 'mp4') {
                 $destinationPath =  $destinationPath.'/'.'video';
               } else {
                 $destinationPath =  $destinationPath.'/'.'image';
               }

               $path = Storage::disk('s3')->put($destinationPath, $request->file);
               $request->merge([
                 'size' => $request->file->getClientSize(),
                 'path' => $path
               ]);

               $fileName = $path;
             } else {
               $fileName = str_slug(Carbon::now()->toDayDateTimeString()).rand(11111, 99999) .'.' . $extension;
               $request->file('file')->move($destinationPath, $fileName);
             }
          }

        echo json_encode(array('file_name'=>$fileName));
      }
    }
}
