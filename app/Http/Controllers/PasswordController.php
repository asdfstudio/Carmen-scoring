<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Http\Controllers\Controller;

use App\User;
use App\Person;
//use App\Judge;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class PasswordController extends Controller
{
    public function edit(FormBuilder $formBuilder)
    {
      $user = Auth::user();

      $form = $formBuilder->create('User\EditPasswordForm', [
        'url' => route('password.update'),
        'model' => $user
      ]);

      return view('profile.edit_password', compact('form', 'user'));
    }


    public function update(Request $request, FormBuilder $formBuilder)
    {
        $user = Auth::user();

				// Validate input
				$form = $formBuilder->create('User\EditPasswordForm');

				// Validate input
				if (!$form->isValid()) {
          return redirect()->back()->withErrors($form->getErrors())->withInput();
        }


        // Update user attributes
        $user->password = bcrypt($request->input('password'));
        $user->save();

				// Redirect
				return redirect()->route('password.edit')->with('success','Password updated!');
    }
}
