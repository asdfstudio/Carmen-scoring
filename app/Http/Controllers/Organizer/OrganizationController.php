<?php

namespace App\Http\Controllers\Organizer;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
				$organization = $request->user()->organization;
        $organization->load('place');

        $this->authorize('show', $organization);

				return view('organization.organizer.show', ['organization' => $organization]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, Request $request)
    {
				$organization = $request->user()->organization;
        $organization->load('place');

				$this->authorize('update',$organization);

				$form = $formBuilder->create('Organization\EditOrganizationForm', [
					'method' => 'PATCH',
					'url' => route('organizer.organization.update'),
					'model' => $organization
				]);

				return view('organization.organizer.edit', compact('form','organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder)
    {
				$organization = $request->user()->organization;

				$this->authorize('update',$organization);

				// Validate input
				$form = $formBuilder->create('Organization\EditOrganizationForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();

				// Update the organization
				$organization->fill($input);
				$organization->save();

        // Update the organization place
        $place_input = $request->input('place');
        $place = $organization->place;

        if($place == false)
          $place = new Place;

        $place->fill($place_input);
        $organization->place()->save($place);

				// Redirect
				return redirect()->route('organizer.organization.show')->with('success', 'Organization updated.');
    }

  /**
   * @param Request $request
   * @return mixed
   */
    public function voteSetting(Request $request) {
      if (Auth::user()->isAdmin()){
        $organizationId = $request->input('organization_id');
        $voteSetting = $request->input('vote_setting');
        $organization = Organization::find($organizationId);
        $organization->vote_setting = $voteSetting;
        $organization->save();
        return $organization;
      }
    }
}
