<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Person;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('showAll', 'App\User');

				$organization_id = $request->user()->organization_id;
        $users = User::with('person')->where('organization_id', $organization_id)->get();

        $deleteUserForm = $formBuilder->create('GenericDeleteForm');

				return view('user.organizer.index', compact('users','deleteUserForm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $user = new User();

        $this->authorize('create',$user);

				$form = $formBuilder->create('User\CreateUserForm', [
					'method' => 'POST',
					'url' => route('organizer.user.store'),
					'model' => $user
				]);

				return view('user.organizer.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormBuilder $formBuilder, Request $request)
    {
        //$this->authorize('update',$organization);

				// Validate input
				$form = $formBuilder->create('User\CreateUserForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$data = $request->input();

        // Create person
        $person = new Person;
        $person->first_name = $data['person']['first_name'];
        $person->last_name = $data['person']['last_name'];
        $person->email = $data['email'];
        $person->save();

        // Create user
				$user = new User;
				$user->email = $data['email'];
				$user->password = bcrypt($data['password']);
				$user->organization_id = Auth::user()->organization_id;
        $user->organization_role = $data['organization_role'];

        $person->user()->save($user);

				return redirect()->route('organizer.user.index')->with('success', 'User created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormBuilder $formBuilder, $id)
    {
        $user = User::find($id);

				$deleteUserForm = $formBuilder->create('GenericDeleteForm', [
					'url' => route('organizer.user.destroy',[$user])
				]);

				return view('user.organizer.show', compact('user','deleteUserForm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
				$user = User::with('person')->find($id);

        $form = $formBuilder->create('User\EditUserForm', [
					'method' => 'PATCH',
					'url' => route('organizer.user.update',[$user]),
					'model' => $user
				]);

				return view('user.organizer.edit', compact('form','user'));
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
        $form = $formBuilder->create('User\EditUserForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$data = $request->input();




        // Update user
				$user = User::find($id);
				$user->email = $data['email'];
        $user->organization_role = $data['organization_role'];

				if($data['password'])
				{
					$user->password = bcrypt($data['password']);
				}

				$user->save();

        // Update person
        $person = $user->person;
        $person->first_name = $data['person']['first_name'];
        $person->last_name = $data['person']['last_name'];
        $person->email = $data['email'];
        $person->save();

				return redirect()->route('organizer.user.index')->with('success', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormBuilder $formBuilder, $id)
    {
        $user = User::find($id);

        $this->authorize('dstroy',$user);

				$user->delete();

				// Set flash data and redirect
				return redirect()->route('organizer.user.index');
    }
}
