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

      $form = $formBuilder->create('User\UserPersonForm', [
        'method' => 'PATCH',
        'url' => route('profile.update'),
        'model' => $user
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

				// Validate input
				$form = $formBuilder->create('User\UserPersonForm', [
          'model' => $user
        ]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = $request->input();
        
        // Is the current user a superadmin (listed in the auth config or else are they editing their own profile)?
        $i_am_superadmin = auth()->user()->isSuperAdmin($user->id);
        $i_am_admin = auth()->user()->isAdmin();
        
        // Update user
        $user->username = $data['username'];
        if(!empty($data['new_password'])){
          $user->password = bcrypt($data['new_password']);
        }
        $user->email = $data['email'];
        if($i_am_admin){
          if(!empty($data['organization_id'])){
            $user->organization_id = $data['organization_id'];
            $user->organization_role = $data['organization_role'];
          } else {
            $user->organization_id = 0;
            $user->organization_role = '';
          }
        }

				$user->save();

        // Update person
        $person = $user->person;

        if($person == false){
          $person = new Person;
        }

        $person->first_name = $data['first_name'];
        $person->last_name = $data['last_name'];
        $person->email = $data['email'];
        $person->emails_additional = $data['emails_additional'];
        $person->tel = $data['tel'];
        $person->save();
        if($i_am_admin){
          if(!empty($data['is_judge'])){
            // Get a list of types for this person, making sure to include type 1 (judge).
            $type_ids = [1];
            foreach($person->types as $type){
              $type_ids[] = $type->id;
            }
            // Only unique values to avoid duplicates.
            $type_ids = array_unique($type_ids);
            // Now update the person's types with all existing types, plus "judge".
            $person->types()->sync($type_ids);
          } else {
            // If the judge checkbox was empty, we must remove the judge type from this person.
            $person->types()->detach(1);
          }
        }

        $user->person()->associate($person);
        $user->save();



				// Redirect
				return redirect()->route('profile.edit')->with('success','Profile updated!');
    }
}
