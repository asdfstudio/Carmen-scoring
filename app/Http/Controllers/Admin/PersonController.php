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

class PersonController extends Controller
{
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return redirect()->route('admin.user.index');
  }
  
  
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(FormBuilder $formBuilder)
  {
    $this->authorize('create','App\Person');

    $person = new Person;

    $form = $formBuilder->create('User\UserPersonForm', [
      'method' => 'POST',
      'url' => route('admin.person.store'),
      'model' => $person
    ]);

    return view('person.admin.create', compact('form'));
  }
  
  
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, FormBuilder $formBuilder)
  {
    $this->authorize('create','App\Person');

    $person = new Person;

    $form = $formBuilder->create('User\UserPersonForm', [
      'model' => $person
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

    // Create the user, if applicable
    if(!empty($data['username'])){
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
    }

    // Set flash data and redirect
    return redirect()->route('admin.user.index')->with('success',"Person record has been created for $person->first_name $person->last_name with email address $person->email.");
  }
  
  
  /**
   * Show the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit(FormBuilder $formBuilder, Person $person)
  {
    $this->authorize('update', $person);

    $form = $formBuilder->create('User\UserPersonForm', [
      'method' => 'PATCH',
      'url' => route('admin.person.update', [$person]),
      'model' => $person
    ]);

    return view('person.admin.edit', compact('form', 'person'));
  }
  
  
  /**
   * Update the specified resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, FormBuilder $formBuilder, Person $person)
  {
    $this->authorize('update', $person);

    // Validate input
    $form = $formBuilder->create('User\UserPersonForm', [
      'model' => $person
    ]);

    // Validate input
    if (!$form->isValid()) {
       return redirect()->back()->withErrors($form->getErrors())->withInput();
    }

    $data = $request->input();

    $user = null;
    
    if($person->user){
      $user = $person->user;
    } elseif(!empty($data['username'])){
      $user = new User;
    }
    
    // Is the current user a superadmin (listed in the auth config or else are they editing their own profile)?
    $i_am_superadmin = auth()->user()->isSuperAdmin($user);

    // Update person
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
    
    // Update user
    if($user){
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
      
      if(empty($person->user)){
        // Save the new user via relationship with person.
        $person->user()->save($user);
      } else {
        // Save an existing user via its own save method.
        $user->save();
      }
    }

    return redirect()->route('admin.user.index')->with('success', "Record for $person->first_name $person->last_name has been updated!");
  }
  
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function search()
  {
    if(isset($_POST['person_search'])){
      
      $person_search = htmlspecialchars($_POST['person_search']);
      //$person_search = $_POST['person_search'];
      
      $people = Person::where('first_name', 'like', $person_search.'%')->get();
      
      $people_data = array();
      
      foreach($people as $person){
        if(null !== $person){
          $data = new \stdClass();
          $data->id = $person->id;
          $data->full_name = $person->first_name . ' ' . $person->last_name;
          $data->first_name = $person->first_name;
          $data->last_name = $person->last_name;
          $data->email = $person->email;
          $data->emails_additional = $person->emails_additional;
          $data->tel = $person->tel;
          $people_data[] = $data;
        }
      }
      
      $people_json = json_encode($people_data);
      
      header('Content-Type: application/json');
      
      echo $people_json;
      
    }
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Person $person)
  {
    $this->authorize('destroy',$person);
    $person->delete();

    return redirect()->route('admin.user.index')->with('success', 'Person deleted!');
  }


}
