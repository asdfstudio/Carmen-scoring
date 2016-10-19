<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Http\Controllers\Controller;

use App\User;
use App\Person;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class ProfileController extends Controller
{
    //

    public function edit(FormBuilder $formBuilder)
    {
      $user = Auth::user();
      $user->load('person');
      $person = $user->person;

      //dd($user);

      $form = $formBuilder->create('Person\EditPersonForm', [
        'url' => route('profile.update'),
        'model' => $person
      ]);

      return view('profile.edit', compact('form', 'user','person'));
    }


    public function update(Request $request, FormBuilder $formBuilder)
    {
        $user = Auth::user();
        $user->load('person');
        $person = Person::find($user->person_id);

        if($person == false)
        {
          $person = new Person;
        }

				//$this->authorize('update',$user);

				// Validate input
				$form = $formBuilder->create('Person\EditPersonForm');

				// Validate input
				if (!$form->isValid()) {
          return redirect()->back()->withErrors($form->getErrors())->withInput();
        }


        // Get the input
				$input = $request->only('first_name','last_name','email');

        // Update/Insert person attributes
        $person->fill($input)->save();

        // Update user attributes
        $user->email = $input['email'];
        $user->person()->associate($person);
        $user->save();



				// Redirect
				return redirect()->route('profile.edit')->with('success','Profile updated!');
    }
}
