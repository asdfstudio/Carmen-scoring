<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Organization;
use App\Person;
use App\Place;
use App\Competition;
use App\Division;
use App\Judge;
use App\Director;
use App\Choreographer;
use App\School;
use App\Choir;
use App\RawScore;
use App\User;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                $organizations = Organization::with('people')->orderBy('name', 'asc')->get();

                // dd($organizations);

                //$this->authorize('showAll',$organizations);

                return view('organization.admin.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Organization');

                $form = $formBuilder->create('OrganizationForm', [
                    'method' => 'POST',
                    'url' => route('admin.organization.store')
                ]);

                return view('organization.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Organization');

                $form = $formBuilder->create('OrganizationForm');

                // Validate input
                if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

                // Get the input
                $input = $request->input();

                //dd($input);


                // Create the organization
                $organization = new Organization;
                $organization->name = $input['name'];
                $organization->save();

        // Create organization place
        $place = new Place($input['place']);
        $organization->place()->save($place);

                // Create the organization person
                //$person_input = $request->input('person');

                //dd($person_input);

                /*$person = new Person;
                $person->first_name = $person_input['first_name'];
                $person->last_name = $person_input['last_name'];
                $person->email = $person_input['email'];
                $organization->people()->save($person);*/

                // Set flash data and redirect
                return redirect()->route('admin.organization.index')->with('success',"$organization->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
                $organization = Organization::find($id);


        // Switch organizations for admin
        $user = Auth::user();
        $user->organization()->associate($organization);
        $user->save();

        return redirect()->route('organizer.competition.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
        //
                $organization = Organization::find($id);

                // method 1
                $this->authorize('update',$organization);


                $form = $formBuilder->create('OrganizationForm', [
                    'method' => 'PATCH',
                    'url' => route('admin.organization.update', [$organization]),
                    'model' => $organization
                ]);

                return view('organization.edit', compact('form','organization'));

                //return view('organization.edit', ['organization' => $organization]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, $id)
    {
                $organization = Organization::find($id);

                // method 1
                $this->authorize('update',$organization);

                // Validate input
                $form = $formBuilder->create('OrganizationForm');

                // Validate input
                if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
                $input = $request->input();

                // Update the organization
                $organization->name = $input['name'];
                $organization->save();


                // Set flash data

                // Redirect
                return redirect()->route('admin.organization.show',[$organization]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organization = Organization::find($id);

                // method 1
                $this->authorize('destroy',$organization);

                dd($organization);
    }

    public function updatePremiumStatus($orgId){

        $organization = Organization::find($orgId);

        if($organization->is_premium == 1){
            $organization->is_premium = 0;
            $organization->save();

            $data['message'] = $organization->name.' has premium access removed.';

        }else{
            $organization->is_premium = 1;
            $organization->save();

            $data['message'] = $organization->name.' has been granted premium access.';
        }
        return response()->json($data, $status = 200, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
