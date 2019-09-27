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

    $form = $formBuilder->create('User\Admin\UserPersonForm', [
      'method' => 'POST',
      'url' => route('admin.person.store'),
      'model' => $person
    ]);

    return view('person.admin.create', compact('form'));
  }
  
  
  /**
   * Show the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit(FormBuilder $formBuilder, Person $person)
  {
    $this->authorize('update', $person);

    $form = $formBuilder->create('User\Admin\UserPersonForm', [
      'method' => 'PATCH',
      'url' => route('admin.person.update', [$person]),
      'model' => $person
    ]);

    $form->modify('password','repeated', [
      'first_options' => [
        'rules' => 'min:4'
      ],
      'second_options' => [
        'rules' => 'required_with:password'
      ]
    ]);

    return view('person.admin.edit', compact('form', 'person'));
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
   * Update the specified resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, FormBuilder $formBuilder, Person $person)
  {
    /*
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
    */
    return redirect()->route('admin.user.index')->with('success', 'Person updated!');
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
