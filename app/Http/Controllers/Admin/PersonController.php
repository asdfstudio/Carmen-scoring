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
    $people = Person::all();
    print_r($people);
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


}
