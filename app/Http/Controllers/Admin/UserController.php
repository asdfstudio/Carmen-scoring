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
    public function index(FormBuilder $formBuilder)
    {
				$users = User::with('organization','person')->withoutGlobalScope('organization')->get();

        $deleteUserForm = $formBuilder->create('GenericDeleteForm');

				return view('user.admin.index', compact('users', 'deleteUserForm'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_new(FormBuilder $formBuilder)
    {
				//$users = User::with('organization','person')->withoutGlobalScope('organization')->get();
				$people = Person::with('user', 'types')->orderBy('last_name')->get();
        
        $deleteUserForm = $formBuilder->create('GenericDeleteForm', ['button_text' => 'Delete User']);
        $deletePersonForm = $formBuilder->create('GenericDeleteForm', ['button_text' => 'Delete Person']);
        
				return view('user.admin.index_new', compact('people', 'deleteUserForm', 'deletePersonForm'));

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

        $form = $formBuilder->create('User\Admin\UserPersonForm', [
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

        $form = $formBuilder->create('User\Admin\CreateUserForm', [
          'model' => $user
        ]);

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

				// Create the user

        $user = new User;
        $user->username = $data['username'];
				$user->email = $data['email'];
				$user->password = bcrypt($data['password']);
        $user->is_admin = $data['is_admin'];
        $user->organization_role = $data['organization_role'];
        $user->organization_id = $data['organization_id'];

        $person->user()->save($user);

				// Set flash data and redirect
				return redirect()->route('admin.user.index')->with('success',"User login has been created for $user->email.");
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

        $this->authorize($user);

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

        $form = $formBuilder->create('User\Admin\UserPersonForm', [
					'method' => 'PATCH',
					'url' => route('admin.user.update', [$user]),
					'model' => $user
				]);
/*
        $form->modify('password','repeated', [
					'first_options' => [
						'rules' => 'min:4'
					],
					'second_options' => [
						'rules' => 'required_with:password'
					]
				]);


        $makeJudgeForm = $formBuilder->create('User\Admin\MakeJudgeForm', [
					'url' => route('admin.user.judge.set', [$user]),
					'model' => $user
				]);
*/
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
				$form = $formBuilder->create('User\Admin\CreateUserForm', [
          'model' => $user
        ]);

        $form->modify('password','repeated', [
					'first_options' => [
						'rules' => 'min:4'
					],
					'second_options' => [
						'rules' => 'required_with:password'
					]
				]);

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = $request->input();

        // Update user
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->is_admin = $data['is_admin'];
        $user->organization_role = $data['organization_role'];
        $user->organization_id = $data['organization_id'];

				if($data['password'])
				{
					$user->password = bcrypt($data['password']);
				}

				$user->save();

        // Update person
        $person = $user->person;

        if($person == false)
        {
          $person = new Person;
        }

        $person->first_name = $data['person']['first_name'];
        $person->last_name = $data['person']['last_name'];
        $person->email = $data['email'];
        $person->save();

        $user->person()->associate($person);
        $user->save();

				return redirect()->route('admin.user.index')->with('success', 'User updated!');
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
				$user->delete();

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
          $user->person->person_type = 'App\Judge';
          $user->person->save();
        }

        return redirect()->route('admin.user.index')->with('success', 'User set up as a judge!');
    }
  
  
    public function getNewUsername()
    {
        $this->authorize('create','App\User');
        
        if(isset($_POST['first_name']) && isset($_POST['last_name'])){
            $first_name = htmlspecialchars($_POST['first_name']);
            $last_name = htmlspecialchars($_POST['last_name']);
            
            echo $this->generateUsername($first_name, $last_name);
        }
    }
    
    
    protected function generateUsername($first_name, $last_name, $number = 0)
    {
        $new_username = preg_replace('/[^a-z]/', '', strtolower($first_name).strtolower($last_name));
        
        if($number){
          $new_username .= $number;
        }
        
        $existing_user = User::where('username', $new_username)->first();
        
        if(count($existing_user)){
          $number++;
          $new_username = $this->generateUsername($first_name, $last_name, $number);
        }
        
        return $new_username;
    }


}
