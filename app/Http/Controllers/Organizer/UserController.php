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

        $this->authorize('create','App\User');

				$form = $formBuilder->create('User\UserPersonForm', [
					'method' => 'POST',
					'url' => route('organizer.user.store'),
					'model' => $user,
          'organization' => Auth::user()->organization_id
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
        $this->authorize('create','App\User');

        $user = new User;

        $form = $formBuilder->create('User\UserPersonForm', [
          'model' => $user
        ]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = $request->input();

        // Create person
        $person = new Person;
        $person->first_name = $data['first_name'];
        $person->last_name = $data['last_name'];
        $person->email = $data['email'];
        $person->emails_additional = $data['emails_additional'];
        $person->tel = $data['tel'];
        $person->save();

				// Create the user
        $user = new User;
        $user->username = $data['username'];
				$user->email = $data['email'];
				$user->password = bcrypt($data['new_password']);
        if(!empty($data['is_admin'])){
          $user->is_admin = 1;
        } else {
          $user->is_admin = 0;
        }
        $user->organization_id = Auth::user()->organization_id;
        if(!empty($data['organization_role'])){
          $user->organization_role = $data['organization_role'];
        } else {
          $user->organization_role = '';
        }

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
        
        $this->authorize('update', $user);
        
        $form = $formBuilder->create('User\UserPersonForm', [
					'method' => 'PATCH',
					'url' => route('organizer.user.update',[$user]),
					'model' => $user,
          'organization' => Auth::user()->organization_id
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
        $user = User::find($id);

				$this->authorize('update', $user);

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
        
        // Update user
        if($i_am_superadmin){
          $user->username = $data['username'];
          if(!empty($data['new_password'])){
            $user->password = bcrypt($data['new_password']);
          }
        }
        if($i_am_superadmin || !$user->is_admin){
          if(!empty($data['is_admin'])){
            $user->is_admin = 1;
          } else {
            $user->is_admin = 0;
          }
        }
        $user->email = $data['email'];
        if(!empty($data['organization_role'])){
          $user->organization_role = $data['organization_role'];
        } else {
          $user->organization_role = '';
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

        $user->person()->associate($person);
        $user->save();

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

        $this->authorize('destroy',$user);
        
        if(!$user->isSuperAdmin()){
          $user->delete();
        }

				// Set flash data and redirect
				return redirect()->route('organizer.user.index');
    }


    public static function getNewUsername($first_name = '', $last_name = '')
    {
        if(empty($first_name) && isset($_POST['first_name'])){
          $first_name = htmlspecialchars($_POST['first_name']);
        }
        
        if(empty($last_name) && isset($_POST['last_name'])){
          $last_name = htmlspecialchars($_POST['last_name']);
        }
        
        echo self::generateUsername($first_name, $last_name);
    }
    
    
    public static function generateUsername($first_name, $last_name, $number = 0)
    {
        $new_username = preg_replace('/[^a-z0-9]/', '', strtolower($first_name).strtolower($last_name));
        
        if($number){
          $new_username .= $number;
        }
        
        $existing_user = User::where('username', $new_username)->first();
        
        if(!empty($existing_user)){
          $number++;
          $new_username = $this->generateUsername($first_name, $last_name, $number);
        }
        
        return $new_username;
    }


}
