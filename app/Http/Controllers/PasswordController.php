<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Http\Controllers\Controller;

use App\User;
use App\Person;
//use App\Judge;

use Auth;
use URL;

use Kris\LaravelFormBuilder\FormBuilder;

class PasswordController extends Controller
{
    protected $user;
    protected $self;

    protected function getUser($user_id = false)
    {
      if($user_id)
      {
        $this->user = User::find($user_id);
        $this->self = false;
      }
      else {
        $this->user = Auth::user();
        $this->self = true;
      }

      return $this->user;
    }

    public function edit(FormBuilder $formBuilder, $user_id = false)
    {
      $user = $this->getUser($user_id);

      if($this->self)
      {
        $form = $formBuilder->create('User\EditPasswordForm', [
          'url' => route('password.update'),
          'model' => $user,
          'data' => ['previous_url' => URL::previous()]
        ]);
      }
      else
      {
        $form = $formBuilder->create('User\EditPasswordForm', [
          'url' => route('user.password.update', [$user]),
          'model' => $user,
          'data' => ['previous_url' => URL::previous()]
        ]);
      }


      return view('profile.edit_password', compact('form', 'user'));
    }


    public function update(Request $request, FormBuilder $formBuilder, $user_id = false)
    {
        $user = $this->getUser($user_id);

				// Validate input
				$form = $formBuilder->create('User\EditPasswordForm', [
          'data' => ['previous_url' => URL::previous()]
        ]);

				// Validate input
				if (!$form->isValid()) {
          return redirect()->back()->withErrors($form->getErrors())->withInput();
        }


        // Update user attributes
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Redirect
        if($this->self)
        {
          return redirect()->route('password.edit')->with('success','Password updated!');
        }
        else
        {
          return redirect($request->input('previous_url'))->with('success','Password updated!');
        }

    }
}
