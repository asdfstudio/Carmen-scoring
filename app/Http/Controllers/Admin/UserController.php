<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Person;
use App\Judge;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_old(FormBuilder $formBuilder)
    {
				$users = User::with('organization','person')->withoutGlobalScope('organization')->get();

        $deleteUserForm = $formBuilder->create('GenericDeleteForm');

				return view('user.admin.index_old', compact('users', 'deleteUserForm'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder)
    {
				//$users = User::with('organization','person')->withoutGlobalScope('organization')->get();
				$people = Person::with('user', 'types')->orderBy('last_name')->get();
        
        $deleteUserForm = $formBuilder->create('GenericDeleteForm', ['button_text' => 'Delete User']);
        $deletePersonForm = $formBuilder->create('GenericDeleteForm', ['button_text' => 'Delete Person']);
        
				return view('user.admin.index', compact('people', 'deleteUserForm', 'deletePersonForm'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\User');

        $user = new User;

        $form = $formBuilder->create('User\UserPersonForm', [
					'method' => 'POST',
					'url' => route('admin.user.store'),
					'model' => $user
				]);

				return view('user.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
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
        if(!empty($data['is_judge'])){
          $person->types()->attach(1);
        }

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
        if(!empty($data['organization_id'])){
          $user->organization_id = $data['organization_id'];
          $user->organization_role = $data['organization_role'];
        } else {
          $user->organization_id = 0;
          $user->organization_role = '';
        }

        $person->user()->save($user);

				// Set flash data and redirect
				return redirect()->route('admin.user.index')->with('success',"User $user->username has been created with email address $user->email.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormBuilder $formBuilder, User $user)
    {
				//$user = User::find($id);

        $this->authorize('show', $user);

        $deleteUserForm = $formBuilder->create('GenericDeleteForm');

				return view('user.admin.show', compact('user','deleteUserForm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, User $user)
    {
				$this->authorize('update', $user);

        $form = $formBuilder->create('User\UserPersonForm', [
					'method' => 'PATCH',
					'url' => route('admin.user.update', [$user]),
					'model' => $user
				]);

        return view('user.admin.edit', compact('form','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, User $user)
    {
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
        if($i_am_superadmin || !$user->is_admin){
          $user->username = isset($data['username']) ? $data['username'] : $user->username;
          if(!empty($data['new_password'])){
            $user->password = bcrypt($data['new_password']);
          }
          if(!empty($data['is_admin'])){
            $user->is_admin = 1;
          } else {
            $user->is_admin = 0;
          }
        }
        $user->email = $data['email'];
        if(!empty($data['organization_id'])){
          $user->organization_id = $data['organization_id'];
          $user->organization_role = $data['organization_role'];
        } else {
          $user->organization_id = 0;
          $user->organization_role = '';
        }

				$user->save();

        // Update person
        $person = $user->person;

        if(empty($person)){
          $person = new Person;
        }

        $person->first_name = $data['first_name'];
        $person->last_name = $data['last_name'];
        $person->email = $data['email'];
        $person->emails_additional = $data['emails_additional'];
        $person->tel = $data['tel'];
        $person->save();
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

        $user->person()->associate($person);
        $user->save();

				return redirect()->route('admin.user.index')->with('success', "User $user->username has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
				$this->authorize('destroy',$user);
        
        if(!$user->isSuperAdmin()){
          $user->delete();
        }
        
        return redirect()->route('admin.user.index')->with('success', 'User deleted!');
    }



    public function makeJudge(User $user)
    {
				$this->authorize('update', $user);

        if($user->isJudge())
        {
          return redirect()->route('admin.user.index')->with('success', 'User is already a judge!');
        }

        // create person/judge if they dont exist
        if($user->person)
        {
          $user->person->types()->attach(1);
          $user->person->save();
        }

        return redirect()->route('admin.user.index')->with('success', 'User set up as a judge!');
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
