<?php

namespace App\Http\Controllers\Organizer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

use App\Audience;
use App\Round;


class AudienceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competition_id, $round_id)
    {
        $audience = Audience::where('audienceable_id',$round_id)
            ->where('audienceable_type', 'App\Round')
            ->first();
        $round = Round::with('competition', 'choirs', 'choirs.directors')->find($round_id);
        $organization_slug = $this->getOrganizationSlug($round->competition->organization);
        return view('competition_round_audience_vote.organizer.index',compact('round', 'organization_slug', 'audience'));
    }

    public function store(Request $request){
        $audience = Audience::where('audienceable_id',$request['round_id'])
            ->where('audienceable_type', 'App\Round')
            ->with('audienceable')
            ->first();

        $data = $request->all();

        if(!$audience){
            $audience = new Audience();
            $round = Round::find($request->get('round_id'));
            $audience->audienceable()->associate($round);
            $audience->competition()->associate($round->competition);
        }
        $audience->alias_name = $request->post('alias_name');
        $audience->is_dark = $request->post('is_dark');
        $audience->banner_type = $request->post('banner_type');
        $audience->banner_upload = $request->post('banner_upload');
        $audience->banner_embed = $request->post('banner_embed');
        $audience->limit_result = $request->post('limit_result');
        $audience->is_premium_vote = $request->post('is_premium_vote', false);
        $audience->is_enabled = $request->post('is_enabled', false);
        $audience->save();

        return redirect()->route('organizer.competition.round.audience.index', [$audience->competition->id, $audience->audienceable->id])->with('success', 'Vote Settings Saved');
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

                // Try to store to S3
                try {
                    $storageDriver = Storage::disk('voting');

                    if (strtolower($extension) === 'mp4') {
                        $destinationPath =  $destinationPath.'/'.'video';
                    } else {
                        $destinationPath =  $destinationPath.'/'.'image';
                    }

                    $path = $storageDriver->put($destinationPath, $request->file);
                    $request->merge([
                        'size' => $request->file->getSize(),
                        'path' => $path
                    ]);

                    $fileName = $path;
                } catch (\Exception $e) {
                    // Store in an uploads folder on the server if S3 unavailable
                    $fileName = Str::slug(Carbon::now()->toDayDateTimeString()).rand(11111, 99999) .'.' . $extension;
                    $request->file('file')->move($destinationPath, $fileName);
                }
            }

            echo json_encode(array('file_name'=>$fileName));
        }
    }
}
