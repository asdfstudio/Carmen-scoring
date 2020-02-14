<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Person;
use App\Judge;
use App\School;
use App\Choir;
use App\ScheduleItem;
use App\Comment;
use App\Recording;

class DeDupController extends Controller
{



  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('dedup.index');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function recordings()
  {
    require_once 'MIME/Type.php';
    $storage_driver = Storage::disk("s3");
    $recordings = Recording::all()->toArray();
    $start = intval($_GET['start']);
    $length = intval($_GET['length']);
    $next_link = url()->current() . '?start='.($start+$length).'&length='.$length;

    $recordings = array_slice($recordings, $start, $length);

    foreach($recordings as &$recording){
      $recording['name'] = basename($recording['url']);
      $tmp = tempnam('/tmp', 'rec_');
      $path = 'recordings/'.$recording['name'];
      file_put_contents($tmp, $storage_driver->get($path));
      $recording['size'] = filesize($tmp);
      $recording['mime_type'] = \MIME_Type::autoDetect($tmp);
      if($recording['mime_type'] === 'application/octet-stream'){
        $recording['mime_type'] = 'audio/mpeg';
        uploadToS3($path, $tmp, ['ContentType' => $recording['mime_type']]);
      }
      unlink($tmp);
    }

    return view('dedup.recordings',['recordings' => $recordings, 'next_link' => $next_link]);
  }

  /**
   * Display a listing of people with duplicates.
   *
   * @return \Illuminate\Http\Response
   */
  public function dup_list()
  {

    $people_grouped = array();
    $has_duplicates = false;
    $group_by = isset($_GET['group_by']) ? strtolower($_GET['group_by']) : null;
    $differences = array();
    $dup_count = 0;

    if(!empty($group_by)){

      $people = Person::with('user', 'subject')->orderBy('id', 'asc')->get();

      foreach($people as $person){
        if($group_by === 'email' || $group_by === 'name'){

          if($group_by === 'email'){
            $group_by_key = $person->email;
          }

          if($group_by === 'name'){
            $group_by_key = $person->first_name . ' ' . $person->last_name;
          }

          $index = base64_encode($group_by_key);

          $keys = array_keys($people_grouped);

          foreach($keys as $key64){

            $key = base64_decode($key64);

            $diff = levenshtein($key, $group_by_key);

            if($diff > 0 && $diff < 3){

              $types_a = array();
              $schools_a = array();

              foreach($people_grouped[$key64] as $record){
                $types = $record->typeNames();
                $types_a = array_merge($types_a, $types);
                $schools = $record->schoolIds();
                $schools_a = array_merge($schools_a, $schools);
              }

              $types_a = array_unique($types_a);
              $schools_a = array_unique($schools_a);

              $types_b = $person->typeNames();
              $schools_b = $person->schoolIds();

              $differences[$key64] = array($key, $types_a, $schools_a, $group_by_key, $types_b, $schools_b, $diff);

              $index = $key64;

            }

          }

          $index = base64_encode($group_by_key);

          $people_grouped[$index][] = $person;

        }

      }

      foreach($people_grouped as $group){
        if(count($group) > 1){
          $has_duplicates = true;
          $dup_count++;
        }
      }

    }

    return view('dedup.dup_list', compact('people_grouped', 'has_duplicates', 'group_by', 'dup_count'));
  }

  /**
   * Convert choir director and choreographer data from being stored in the people table
   * to new bridge tables called choir_director and choir_choreographer. Also convert
   * the data about a person's type to a new person_type table.
   *
   * @return \Illuminate\Http\Response
   */
  public function convert_person_type_choir()
  {

    ini_set('max_execution_time', 3600);

    // Arrays of info to be passed into the view.
    $judges = array();
    $directors = array();
    $choreographers = array();

    // Check if Run or Dry Run state is active.  (And don't allow both.)
    $run = isset($_GET['run']) ? true : false;
    $dryrun = isset($_GET['dryrun']) ? true : false;
    $run_dryrun_error = $run && $dryrun;
    $prefix = $dryrun ? '<strong>DRY RUN:</strong> ' : '';

    if(($run || $dryrun) && !$run_dryrun_error){

        $people = Person::with('subject', 'types')->orderBy('id', 'asc')->get();

        foreach($people as $person){
        //for($i = 0; $i < 21; $i++){
        //  $person = $people[$i];

          if($person->person_type === 'App\Judge'){

            $info = new \stdClass();

            $info->intro = $person->getFullNameAttribute().' (ID '.$person->id.') is a judge.';

            $info->run_messages = array();

            if($person->getIsJudgeAttribute()){
              $info->run_messages[] = $prefix . 'This person is already recorded as a judge in the new table structure.';
            } else {
              $info->run_messages[] = $prefix . 'Beginning conversion.';
              $info->run_messages[] = $prefix . '$person is set to the person with ID '.$person->id.'.';
              $info->run_messages[] = $prefix . '<code>$person->types()->attach(1)</code> &nbsp;[Type 1 is for Judge]';
              if($run){
                $result = $person->types()->attach(1);
              }
            }

            $judge = Judge::with('divisions')->find($person->id);
            $divisions = $judge->divisions;
            $info->divisions = array();

            if($divisions){
              foreach($divisions as $division){
                $info->divisions[] = $division->name.' ('.$division->id.')';
              }
            } else {
              $info->divisions[] = 'None';
            }

            $judges[] = $info;

          }

          if($person->person_type === 'App\Director'){

            $info = new \stdClass();

            $info->intro = $person->getFullNameAttribute().' (ID '.$person->id.') is a choir director.';

            $info->run_messages = array();

            if($person->getIsDirectorAttribute()){
              $info->run_messages[] = $prefix . 'This person is already recorded as a director in the new table structure.';
            } else {
              $info->run_messages[] = $prefix . 'Beginning conversion.';
              $info->run_messages[] = $prefix . '$person is set to the person with ID '.$person->id.'.';
              $info->run_messages[] = $prefix . '<code>$person->types()->attach(2)</code> &nbsp;[Type 2 is for Director]';
              if($run){
                $person->types()->attach(2);
              }
            }

            if(get_class($person->subject) === 'App\Choir'){

              $choir = Choir::with('school', 'directors')->find($person->subject->id);

              if($choir){

                $info->choir = 'Choir: '.$choir->name.' ('.$choir->id.')';

                $school = $choir->school;

                if($school){

                  $info->school = 'School: '.$school->name.' ('.$school->id.')';

                }

                if($choir->directors->contains('id', $person->id)){
                  $info->run_messages[] = $prefix . 'Person '.$person->id.' is already assigned as a director of choir '.$choir->id.' in the new table structure.';
                } else {
                  $info->run_messages[] = $prefix . 'Assigning person '.$person->id.' as a director of choir '.$choir->id.'.';
                  $info->run_messages[] = $prefix . '$choir is set to the choir with ID '.$choir->id.'.';
                  $info->run_messages[] = $prefix . '<code>$choir->directors()->attach('.$person->id.')</code>';
                  if($run){
                    $choir->directors()->attach($person->id);
                  }
                }

              } else {

                $info->choir = 'Choir: None';
                $info->school = 'School: None';

              }
            }

            $directors[] = $info;

          }

          if($person->person_type === 'App\Choreographer'){

            $info = new \stdClass();

            $info->intro = $person->getFullNameAttribute().' (ID '.$person->id.') is a choir choreographer.';

            $info->run_messages = array();

            if($person->getIsDirectorAttribute()){
              $info->run_messages[] = $prefix . 'This person is already recorded as a choreographer in the new table structure.';
            } else {
              $info->run_messages[] = $prefix . 'Beginning conversion.';
              $info->run_messages[] = $prefix . '$person is set to the person with ID '.$person->id.'.';
              $info->run_messages[] = $prefix . '<code>$person->types()->attach(3)</code> &nbsp;[Type 3 is for Choreographer]';
              if($run){
                $person->types()->attach(3);
              }
            }

            if(get_class($person->subject) === 'App\Choir'){

              $choir = Choir::with('school', 'choreographers')->find($person->subject->id);

              if($choir){

                $info->choir = 'Choir: '.$choir->name.' ('.$choir->id.')';

                $school = $choir->school;

                if($school){

                  $info->school = 'School: '.$school->name.' ('.$school->id.')';

                }

                if($choir->choreographers->contains('id', $person->id)){
                  $info->run_messages[] = $prefix . 'Person '.$person->id.' is already assigned as a choreographer of choir '.$choir->id.' in the new table structure.';
                } else {
                  $info->run_messages[] = $prefix . 'Assigning person '.$person->id.' as a choreographer of choir '.$choir->id.'.';
                  $info->run_messages[] = $prefix . '$choir is set to the choir with ID '.$choir->id.'.';
                  $info->run_messages[] = $prefix . '<code>$choir->choreographers()->attach('.$person->id.')</code>';
                  if($run){
                    $choir->choreographers()->attach($person->id);
                  }
                }

              } else {

                $info->choir = 'Choir: None';
                $info->school = 'School: None';

              }
            }

            $choreographers[] = $info;

          }

        }

    }

    return view('dedup.convert_person_type_choir', compact('run', 'dryrun', 'run_dryrun_error', 'judges', 'directors', 'choreographers'));
  }

  /**
   * Find duplicate records of judges, directors, and choreographers and merge them
   * together, using the new bridge tables to preserve many-to-many relationships.
   *
   * @return \Illuminate\Http\Response
   */
  public function merge_dups()
  {

    ini_set('max_execution_time', 3600);

    // Check if Run or Dry Run state is active.  (And don't allow both.)
    $run = isset($_GET['run']) ? true : false;
    $dryrun = isset($_GET['dryrun']) ? true : false;
    $run_dryrun_error = $run && $dryrun;
    $thorough = isset($_GET['thorough']) ? true : false;
    $prefix = $dryrun ? '<strong>DRY RUN:</strong> ' : '';


    if(($run || $dryrun) && !$run_dryrun_error){

        $people = Person::with('user', 'types')->orderBy('id', 'asc')->get();

        $people_grouped = array();
        $people_grouped_by_email = array();

        foreach($people as $person){

          // Make a new object where we can store some computationally expensive
          // properties that will be used in multiple places.  But store the
          // whole Person object in the "person" property because we'll need it
          // when it is time to interact with the database to make updates.
          $individual = new \stdClass();
          $individual->person = $person;
          $individual->id = $person->id;
          $individual->first_name = $person->first_name;
          $individual->last_name = $person->last_name;
          $individual->full_name = $person->first_name . ' ' . $person->last_name;
          $individual->email = $person->email;
          $individual->tel = $person->tel;
          $individual->schools = $person->schoolIds();
          $individual->user = $person->user;
          $individual->judge = $person->judge();
          $individual->director = $person->director();
          $individual->choreographer = $person->choreographer();
          $individual->updated_at = $person->updated_at;

          $people_grouped_by_email[md5($person->email)][] = $individual;
        }

        $people_grouped_a = $people_grouped_by_email;
        $people_grouped_b = $people_grouped_by_email;
        $redundant_keys = array();

        if($thorough){

          foreach($people_grouped_by_email as $key_a => $group_a){

            if(!in_array($key_a, $redundant_keys)){

              $name_a = $group_a[0]->full_name;
              $ids_a = array();
              $schools_a = array();
              foreach($group_a as $group_a_person){
                $schools_a = array_merge($schools_a, $group_a_person->schools);
                $ids_a[] = $group_a_person->id;
              }

              //echo '<p>' . $name_a . ' | IDs: ' . implode(', ', $ids_a) . ' | Email: ' . $group_a[0]->email . ' | Schools: ' . implode(', ', $schools_a) . '</p><ul>';

              foreach($people_grouped_by_email as $key_b => $group_b){

                if(strcmp($key_a, $key_b) !== 0){

                  $school_overlap = false;
                  $same_name = false;

                  $name_b = $group_b[0]->full_name;
                  $ids_b = array();
                  $schools_b = array();
                  foreach($group_b as $group_b_person){
                    $schools_b = array_merge($schools_b, $group_b_person->schools);
                    $ids_b[] = $group_b_person->id;
                  }

                  foreach($schools_b as $school_id){
                    if(in_array($school_id, $schools_a)){
                      $school_overlap = true;
                      break;
                    }
                  }

                  $same_name = strcmp($name_a, $name_b) === 0 ? true : false;

                  if($school_overlap && $same_name){
                    $group_a = array_merge($group_a, $group_b);
                    $redundant_keys[] = $key_b;
                    //echo '<li>' . $name_b . ' | IDs: ' . implode(', ', $ids_b) . ' | Email: ' . $group_b[0]->email . ' | School Overlap: ' . intval($school_overlap) . ' (schools: ' . implode(', ', $schools_b) . ') | Similar Name: ' . intval($same_name) . '</li>';
                  }


                }

              }

              $people_grouped[$key_a] = $group_a;

              //echo '</ul>';

            }

          }

          //die("Done!");
          //dd($people_grouped);

        } else {
          $people_grouped = $people_grouped_by_email;
        }

        $people_merged_info = array();

        foreach($people_grouped as $group){

          $info = new \stdClass();

          $info->id = null;
          $info->email = $group[0]->email;
          $info->emails_additional = array();
          $info->people_list = array();
          $info->user_id = null;
          $info->users_list = array();
          $info->first_name = null;
          $info->last_name = null;
          $info->tel = null;
          $info->divisions_judged = array();
          $info->divisions_pivot_captions = array();
          $info->comments = array();
          $info->choirs_directed = array();
          $info->choirs_choreographed = array();
          $info->types = array();
          $info->updated_at = '0000-00-00 00:00:00';
          $info->single_or_multiple = '';

          foreach($group as $record){

            // Get the first person ID or else the person ID that is already associated with a user account.
            if(null === $info->id || $record->user){
              $info->id = $record->id;
            }

            // If there are multiple emails represented because of a "thorough" search, save the extras.
            if(strtolower($record->email) !== strtolower($info->email) && !in_array($record->email, $info->emails_additional)){
              $info->emails_additional[] = $record->email;
            }

            $info->people_list[] = $record->id;

            // Get the user ID, if applicable, and also keep track if there are more than one users associated with a person.
            if($record->user){
              $info->user_id = $record->user->id;
              $info->users_list[] = $record->user->id;
            }

            // Get the first or most recently updated name.
            if(null === $info->first_name || ($record->first_name && $record->updated_at > $info->updated_at)){
              $info->first_name = $record->first_name;
            }

            // Get the first or most recently updated name.
            if(null === $info->last_name || ($record->last_name && $record->updated_at > $info->updated_at)){
              $info->last_name = $record->last_name;
            }

            // Get the first or most recently updated phone number.
            if(null === $info->tel || ($record->tel && $record->updated_at > $info->updated_at)){
              $info->tel = $record->tel;
            }

            // Get list of divisions for which this person is a judge.
            if(null !== $record->judge){


              // Add the judge type ID to the list of types for this person.
              if(!in_array(1, $info->types)){
                $info->types[] = 1;
              }

              foreach($record->judge->divisions as $division){

                if(!in_array($division->id, $info->divisions_judged)){
                  // Make a simple array containing each division ID without duplicates.
                  $info->divisions_judged[] = $division->id;
                }

                $info->divisions_pivot_captions[] = ['division_id' => $division->id, 'caption_id' => $division->pivot->caption_id];

              }

              foreach($record->judge->comments as $comment){
                // Note: Because comments belong to judges instead of the other way around,
                // we need to store the whole comment model instead of just the ID.
                // Later in the script, we will loop through all the comments and use the
                // associate() method to attach them to the main person record.
                $info->comments[] = $comment;
              }

            }

            // Get list of choirs for which this person is a director.
            if(null !== $record->director){
              // Add the director type ID to the list of types for this person.
              if(!in_array(2, $info->types)){
                $info->types[] = 2;
              }
              foreach($record->director->choirs as $choir){
                $info->choirs_directed[] = $choir->id;
              }
            }

            // Get list of choirs for which this person is a choreographer.
            if(null !== $record->choreographer){
              // Add the choreographer type ID to the list of types for this person.
              if(!in_array(3, $info->types)){
                $info->types[] = 3;
              }
              foreach($record->choreographer->choirs as $choir){
                $info->choirs_choreographed[] = $choir->id;
              }
            }

            // Note the timestamp of the most recent record update.
            $info->updated_at = ($record->updated_at > $info->updated_at) ? $record->updated_at : $info->updated_at;

          }

          // Set a full name property for convenience.
          $info->full_name = $info->first_name.' '.$info->last_name;

          // Get type names for use in logging messages.
          $info->type_names = array();
          foreach($info->types as $type_id){
            if(1 === $type_id){
              $info->type_names[] = 'judge';
            }
            if(2 === $type_id){
              $info->type_names[] = 'director';
            }
            if(3 === $type_id){
              $info->type_names[] = 'choreographer';
            }
          }

          // Get the person object of the master record.
          foreach($group as $record){
            if($info->id === $record->id){
              $person = $record->person;
              break;
            }
          }

          $info->run_messages = array();

          if(1 === count($group)){

            $info->single_or_multiple = 'single';
            $info->run_messages[] = $prefix . 'No duplicates for this person. No merge is necessary.';

          } else {

            $info->single_or_multiple = 'multiple';
            $info->run_messages[] = $prefix . 'Starting data merge into person ID '.$info->id.', represented by $person.';

            if(count($info->emails_additional)){
              $emails_additional_list = implode(', ', $info->emails_additional);
              $info->run_messages[] = $prefix . 'Using '.$info->email.' as primary email address.';
              $info->run_messages[] = $prefix . 'Saving '.$emails_additional_list.' as additional email address(es).';
              $info->run_messages[] = $prefix . '<code>$person->emails_additional = "'.$emails_additional_list.'"</code>';
              $info->run_messages[] = $prefix . '<code>$person->save()</code>';
              if($run){
                $person->emails_additional = $emails_additional_list;
                $person->save();
              }
            }

            if($info->tel){
              $info->run_messages[] = $prefix . 'Using '.$info->tel.' as phone number.';
              $info->run_messages[] = $prefix . '<code>$person->tel = '.$info->tel.'</code>';
              $info->run_messages[] = $prefix . '<code>$person->save()</code>';
              if($run){
                $person->tel = $info->tel;
                $person->save();
              }
            }

            $info->run_messages[] = $prefix . 'Resetting applicable types for $person ('.implode(', ', $info->type_names).').';
            $info->run_messages[] = $prefix . '<code>$person->types()->sync([])</code> &nbsp;[First, empty the types to avoid duplicates]';
            $info->run_messages[] = $prefix . '<code>$person->types()->sync('.implode(', ', $info->types).')</code>';
            if($run){
              // $person will now have exactly the types that are contained in the array $info->types.
              $person->types()->sync([]);
              $person->types()->sync($info->types);
              // Refresh the person data.
              $person = Person::with('user', 'types')->find($person->id);
            }

            if($info->divisions_pivot_captions || $info->comments){
              $info->run_messages[] = $prefix . 'Syncing judge data (divisions, captions, and comments).';
            }

            if($info->divisions_pivot_captions){
              $info->run_messages[] = $prefix . '<code>[for each division/caption combination] $person->judge()->divisions()->attach($division_id, [\'caption_id\' => $caption_id])</code>';
              if($run){
                foreach($info->divisions_pivot_captions as $div_cap){
                  $division_id = $div_cap['division_id'];
                  $caption_id = $div_cap['caption_id'];
                  $existing = Judge::with('divisions', 'captions')->has('divisions', '=', $division_id)->has('captions', '=', $caption_id)->get();
                  if(empty($existing)){
                    $person->judge()->divisions()->attach($division_id, ['caption_id' => $caption_id]);
                  } else {
                    //dd($existing);
                  }
                }
              }
            }

            if($info->comments){
              $info->run_messages[] = $prefix . '<code>[for each comment] $comment->judge()->associate($person)</code>';
              if($run){
                foreach($info->comments as $comment){
                  $comment->judge()->associate($person);
                }
              }
            }

            if($info->choirs_directed){
              $info->run_messages[] = $prefix . 'Syncing all choirs directed.';
              $info->run_messages[] = $prefix . '<code>$person->director()->choirs()->sync(['.implode(', ', $info->choirs_directed).'])</code>';
              if($run){
                $person->director()->choirs()->sync($info->choirs_directed);
              }
            }

            if($info->choirs_choreographed){
              $info->run_messages[] = $prefix . 'Syncing all choirs choreographed.';
              $info->run_messages[] = $prefix . '<code>$person->choreographer()->choirs()->sync(['.implode(', ', $info->choirs_choreographed).'])</code>';
              if($run){
                $person->choreographer()->choirs()->sync($info->choirs_choreographed);
              }
            }

            foreach($group as $duplicate){
              if($info->id !== $duplicate->id){

                $info->run_messages[] = $prefix . 'Removing data from duplicate person ID ' . $duplicate->id . ' represented by $duplicate.';

                if(null !== $duplicate->judge){
                  $info->run_messages[] = $prefix . 'Removing judge records by syncing an empty array.';
                  $info->run_messages[] = $prefix . '<code>$duplicate->judge()->divisions()->sync([])</code>';
                  if($run){
                    $duplicate->judge->divisions()->sync([]);
                  }
                }

                if(null !== $duplicate->director){
                  $info->run_messages[] = $prefix . 'Removing director records by syncing an empty array.';
                  $info->run_messages[] = $prefix . '<code>$duplicate->director()->choirs()->sync([])</code>';
                  if($run){
                    $duplicate->director->choirs()->sync([]);
                  }
                }

                if(null !== $duplicate->choreographer){
                  $info->run_messages[] = $prefix . 'Removing choreographer records by syncing an empty array.';
                  $info->run_messages[] = $prefix . '<code>$duplicate->choreographer()->choirs()->sync([])</code>';
                  if($run){
                    $duplicate->choreographer->choirs()->sync([]);
                  }
                }

                $info->run_messages[] = $prefix . 'Deleting duplicate person ID ' . $duplicate->id . ' from the database.';
                $info->run_messages[] = $prefix . '<code>$duplicate->destroy()</code>';
                if($run){
                  $duplicate->person->delete();
                }

              }
            }

          }

          $people_merged_info[] = $info;

        }

    }

    return view('dedup.merge_dups', compact('run', 'dryrun', 'run_dryrun_error', 'people', 'people_merged_info'));
  }

  /**
   * Find potential duplicate records and allow an admin to manually choose which ones to merge.
   *
   * @return \Illuminate\Http\Response
   */
  public function merge_dups_manual()
  {

    ini_set('max_execution_time', 3600);

    $people_merged_info = array();

    if(isset($_POST['duplicates'])){

      $duplicates = $_POST['duplicates'];

      foreach($duplicates as $group){

        $info = new \stdClass();

        $info->id = null;
        $info->email = null;
        $info->emails_additional = array();
        $info->people_list = array();
        $info->user_id = null;
        $info->users_list = array();
        $info->first_name = null;
        $info->last_name = null;
        $info->tel = null;
        $info->divisions_judged = array();
        $info->divisions_pivot_captions = array();
        $info->comments = array();
        $info->choirs_directed = array();
        $info->choirs_choreographed = array();
        $info->types = array();
        $info->updated_at = '0000-00-00 00:00:00';

        foreach($group as $id){

          $record = Person::with('user', 'types')->find($id);

          // Get the first person ID or else the person ID that is already associated with a user account.
          if(null === $info->id || $record->user){
            $info->id = $record->id;
            $info->email = $record->email;
            $person = $record;
          }

          // If there are multiple emails represented because of a "thorough" search, save the extras.
          if(!in_array($record->email, $info->emails_additional)){
            $info->emails_additional[] = $record->email;
          }

          $info->people_list[] = $record->id;

          // Get the user ID, if applicable, and also keep track if there are more than one users associated with a person.
          if($record->user){
            $info->user_id = $record->user->id;
            $info->users_list[] = $record->user->id;
          }

          // Get the first or most recently updated name.
          if(null === $info->first_name || ($record->first_name && $record->updated_at > $info->updated_at)){
            $info->first_name = $record->first_name;
          }

          // Get the first or most recently updated name.
          if(null === $info->last_name || ($record->last_name && $record->updated_at > $info->updated_at)){
            $info->last_name = $record->last_name;
          }

          // Get the first or most recently updated phone number.
          if(null === $info->tel || ($record->tel && $record->updated_at > $info->updated_at)){
            $info->tel = $record->tel;
          }

          // Get list of divisions for which this person is a judge.
          if(null !== $record->judge()){


            // Add the judge type ID to the list of types for this person.
            if(!in_array(1, $info->types)){
              $info->types[] = 1;
            }

            foreach($record->judge()->divisions as $division){

              if(!in_array($division->id, $info->divisions_judged)){
                // Make a simple array containing each division ID without duplicates.
                $info->divisions_judged[] = $division->id;
              }

              $info->divisions_pivot_captions[] = ['division_id' => $division->id, 'caption_id' => $division->pivot->caption_id];

            }

            foreach($record->judge()->comments as $comment){
              // Note: Because comments belong to judges instead of the other way around,
              // we need to store the whole comment model instead of just the ID.
              // Later in the script, we will loop through all the comments and use the
              // associate() method to attach them to the main person record.
              $info->comments[] = $comment;
            }

          }

          // Get list of choirs for which this person is a director.
          if(null !== $record->director()){
            // Add the director type ID to the list of types for this person.
            if(!in_array(2, $info->types)){
              $info->types[] = 2;
            }
            foreach($record->director()->choirs as $choir){
              $info->choirs_directed[] = $choir->id;
            }
          }

          // Get list of choirs for which this person is a choreographer.
          if(null !== $record->choreographer()){
            // Add the choreographer type ID to the list of types for this person.
            if(!in_array(3, $info->types)){
              $info->types[] = 3;
            }
            foreach($record->choreographer()->choirs as $choir){
              $info->choirs_choreographed[] = $choir->id;
            }
          }

          // Note the timestamp of the most recent record update.
          $info->updated_at = ($record->updated_at > $info->updated_at) ? $record->updated_at : $info->updated_at;

        }

        // Get rid of the duplicate instance of the primary account email from the emails_additional array.
        for($i = 0; $i < count($info->emails_additional); $i++){
          if($info->emails_additional[$i] === $info->email){
            unset($info->emails_additional[$i]);
            break;
          }
        }

        // Set a full name property for convenience.
        $info->full_name = $info->first_name.' '.$info->last_name;

        //dd($info);

        // Begin merging data.

        // Merge in additional emails.
        if(count($info->emails_additional)){
          $emails_additional_list = implode(', ', $info->emails_additional);
          $person->emails_additional = $emails_additional_list;
          $person->save();
        }

        if($info->tel){
          $person->tel = $info->tel;
          $person->save();
        }

        // $person will now have exactly the types that are contained in the array $info->types.
        $person->types()->sync([]);
        $person->types()->sync($info->types);
        // Refresh the person data.
        $person = Person::with('user', 'types')->find($person->id);

        // Merge division data for judges.
        foreach($info->divisions_pivot_captions as $div_cap){
          $division_id = $div_cap['division_id'];
          $caption_id = $div_cap['caption_id'];
          $existing = Judge::with('divisions', 'captions')->has('divisions', '=', $division_id)->has('captions', '=', $caption_id)->get();
          if(empty($existing)){
            $person->judge()->divisions()->attach($division_id, ['caption_id' => $caption_id]);
          }
        }

        foreach($info->comments as $comment){
          $comment->judge()->associate($person);
        }

        if($info->choirs_directed){
          $person->director()->choirs()->sync($info->choirs_directed);
        }

        if($info->choirs_choreographed){
          $person->choreographer()->choirs()->sync($info->choirs_choreographed);
        }

        foreach($group as $id){
          if($info->id !== intval($id)){

            $duplicate_person = Person::find($id);

            if(null !== $duplicate_person->judge()){
              $duplicate_person->judge()->divisions()->sync([]);
            }

            if(null !== $duplicate_person->director()){
              $duplicate_person->director()->choirs()->sync([]);
            }

            if(null !== $duplicate_person->choreographer()){
              $duplicate_person->choreographer()->choirs()->sync([]);
            }

            $duplicate_person->delete();

          }
        }

        $people_merged_info[] = $info;

      }

    }

    //dd($people_merged_info);

    $people = Person::with('types')->orderBy('id', 'asc')->get();

    $people_grouped = array();
    $people_grouped_by_email = array();
    $potential_dup_count = 0;

    foreach($people as $person){
      $people_grouped_by_email[md5($person->email)][] = $person;
    }

    $people_grouped_a = $people_grouped_by_email;
    $people_grouped_b = $people_grouped_by_email;
    $redundant_keys = array();

    foreach($people_grouped_by_email as $key_a => $group_a){

      if(!in_array($key_a, $redundant_keys)){

        $name_a = $group_a[0]->first_name . ' ' . $group_a[0]->last_name;

        foreach($people_grouped_by_email as $key_b => $group_b){

          if(strcmp($key_a, $key_b) !== 0){

            $similar_name = false;

            $name_b = $group_b[0]->first_name . ' ' . $group_b[0]->last_name;

            $similar_name = levenshtein(strtolower($name_a), strtolower($name_b)) < 3 ? true : false;

            if($similar_name){
              $group_a = array_merge($group_a, $group_b);
              $redundant_keys[] = $key_b;
              $potential_dup_count++;
            }

          }

        }

        $people_grouped[$key_a] = $group_a;

      }

    }

    //dd($people_grouped);

    return view('dedup.merge_dups_manual', compact('people_grouped', 'potential_dup_count', 'people_merged_info'));
  }

  /**
   * Delete blank records in the people table.
   *
   * @return \Illuminate\Http\Response
   */
  public function delete_blanks()
  {

    $blanks = Person::where('first_name', '')->where('last_name', '')->where('email', '')->get();
    $blank_count = count($blanks);

    foreach($blanks as $blank){
      $blank->delete();
    }

    return view('dedup.delete_blanks', compact('blank_count'));
  }

  /**
   * Display a listing of people with duplicates.
   *
   * @return \Illuminate\Http\Response
   */
  public function dup_list_schools()
  {

    ini_set('max_execution_time', 3600);

    $schools_grouped = array();
    $has_duplicates = false;
    $differences = array();
    $dup_count = 0;

    $schools = School::with('choirs', 'place')->orderBy('id', 'asc')->get();

    foreach($schools as $school){

      $key = base64_encode($school->name . ', ' . $school->place->city_state());

      foreach($schools_grouped as $existing_key => $group){
        foreach($group as $comp_school){
          // Calculate the difference in names, with a special modification to avoid Jr and Sr from being mistaken as a typo.
          $diff_name = levenshtein(preg_replace('/\sjr\.?\s/i', 'JuniorNotSenior', $school->name), preg_replace('/\sjr\.?\s/i', 'JuniorNotSenior', $comp_school->name));
          $diff_city = (empty($school->place->city) || empty($comp_school->place->city)) ? 0 : levenshtein($school->place->city, $comp_school->place->city);
          $diff_state = (empty($school->place->state_full_name()) || empty($comp_school->place->state_full_name())) ? 0 : levenshtein($school->place->state_full_name(), $comp_school->place->state_full_name());

          if($diff_name < 3 && $diff_city < 3 && $diff_state < 3){
            $key = $existing_key;
            break 2;
          }
        }
      }

      $schools_grouped[$key][] = $school;

    }

    foreach($schools_grouped as $group){
      if(count($group) > 1){
        $has_duplicates = true;
        $dup_count++;
      }
    }

    return view('dedup.dup_list_schools', compact('schools_grouped', 'has_duplicates', 'dup_count'));
  }

  /**
   * Find potential duplicate records and allow an admin to manually choose which ones to merge.
   *
   * @return \Illuminate\Http\Response
   */
  public function merge_dup_schools_manual()
  {

    ini_set('max_execution_time', 3600);

    $group_by = isset($_GET['group_by']) ? strtolower($_GET['group_by']) : null;
    $schools_merged_info = array();
    $schools_grouped = array();
    $dup_count = 0;
    $has_duplicates = false;

    if(isset($_POST['duplicates'])){

      $duplicates = $_POST['duplicates'];

      foreach($duplicates as $group){

        $info = new \stdClass();

        $info->id = null;
        $info->school_list = array();
        $info->name = null;
        $info->choirs = array();
        $info->place = new \stdClass();
        $info->place->address = null;
        $info->place->address_2 = null;
        $info->place->city = null;
        $info->place->state = null;
        $info->place->postal_code = null;
        $info->updated_at = '0000-00-00 00:00:00';

        foreach($group as $id){

          $record = School::with('choirs', 'place')->find($id);

          // Get the first person ID or else the person ID that is already associated with a user account.
          if(null === $info->id){
            $info->id = $record->id;
            $school = $record;
          }

          $info->school_list[] = $record->id;

          // Get the first or most recently updated name.
          if(null === $info->name || ($record->name && $record->updated_at > $info->updated_at)){
            $info->name = trim($record->name);
          }

          // Get list of all choirs associated with the schools.
          foreach($record->choirs as $choir){
            $info->choirs[] = $choir->id;
          }

          // Get the first or most recently updated location details.
          if(null === $info->place->address || ($record->place->address && $record->updated_at > $info->updated_at)){
            $info->place->address = trim($record->place->address);
          }
          if(null === $info->place->address_2 || ($record->place->address_2 && $record->updated_at > $info->updated_at)){
            $info->place->address_2 = trim($record->place->address_2);
          }
          if(null === $info->place->city || ($record->place->city && $record->updated_at > $info->updated_at)){
            $info->place->city = trim($record->place->city);
          }
          if(null === $info->place->state || ($record->place->state_abbreviation() && $record->updated_at > $info->updated_at)){
            $info->place->state = trim($record->place->state_abbreviation());
          }
          if(null === $info->place->postal_code || ($record->place->postal_code && $record->updated_at > $info->updated_at)){
            $info->place->postal_code = trim($record->place->postal_code);
          }

          // Note the timestamp of the most recent record update.
          $info->updated_at = ($record->updated_at > $info->updated_at) ? $record->updated_at : $info->updated_at;

        }

        //dd($info);

        // Begin merging data.

        $school->name = $info->name;
        $school->save();

        $info->choirs = array_unique($info->choirs);
        foreach($info->choirs as $choir_id){
          $choir = Choir::find($choir_id);
          $choir->school_id = $school->id;
          $choir->save();
        }

        $school->place->address = $info->place->address;
        $school->place->address_2 = $info->place->address_2;
        $school->place->city = $info->place->city;
        $school->place->state = $info->place->state;
        $school->place->postal_code = $info->place->postal_code;
        $school->place->save();

        // Delete the duplicate schools.
        foreach($group as $id){
          if($info->id !== intval($id)){

            $duplicate_school = School::find($id);

            $duplicate_school->place->delete();

            $duplicate_school->delete();

          }
        }

        $schools_merged_info[] = $info;

      }

      //dd($schools_merged_info);

    }

    if(!empty($group_by)){

      $schools = School::with('choirs', 'place')->orderBy('id', 'asc')->get();

      if($group_by === 'name' || $group_by === 'location' || $group_by === 'both'){

        foreach($schools as $school){

          if($group_by === 'name'){
            $group_by_key = $school->name;
          }

          if($group_by === 'location'){
            $group_by_key = $school->place->city_state();
          }

          if($group_by === 'both'){
            $group_by_key = $school->name . ', ' . $school->place->city_state();
          }

          $key = base64_encode($group_by_key);

          foreach($schools_grouped as $existing_key => $group){
            foreach($group as $comp_school){

              $diff_name = 0;
              $diff_city = 0;
              $diff_state = 0;

              if($group_by === 'name' || $group_by === 'both'){
                // Calculate the difference in names, with a special modification to avoid Jr and Sr from being mistaken as a typo.
                $diff_name = levenshtein(preg_replace('/\sjr\.?\s/i', 'JuniorNotSenior', $school->name), preg_replace('/\sjr\.?\s/i', 'JuniorNotSenior', $comp_school->name));
              }

              if($group_by === 'location' || $group_by === 'both'){
                $diff_city = levenshtein($school->place->city, $comp_school->place->city);
                $diff_state = levenshtein($school->place->state_full_name(), $comp_school->place->state_full_name());

                // If a state value is blank but the cities match (or vice versa), then consider this a potential duplicate.
                if((empty($school->place->state_full_name()) || empty($comp_school->place->state_full_name())) && $diff_city < 2){
                  $diff_state = 0;
                }elseif((empty($school->place->city) || empty($comp_school->place->city)) && $diff_state < 2){
                  $diff_city = 0;
                }
              }

              if($group_by === 'name'){
                if($diff_name < 3){
                  $key = $existing_key;
                  break 2;
                }
              }

              if($group_by === 'location'){
                if($diff_city < 2 && $diff_state < 2){
                  $key = $existing_key;
                  break 2;
                }
              }

              if($group_by === 'both'){
                if($diff_name < 3 && $diff_city < 2 && $diff_state < 2){
                  $key = $existing_key;
                  break 2;
                }
              }

            }
          }

          $schools_grouped[$key][] = $school;

        }

        foreach($schools_grouped as $group){
          if(count($group) > 1){
            $has_duplicates = true;
            $dup_count++;
          }
        }

      }

    }

    return view('dedup.merge_dup_schools_manual', compact('schools_grouped', 'schools_merged_info', 'dup_count', 'has_duplicates'));
  }

  /**
   * Find potential duplicate records and allow an admin to manually choose which ones to merge.
   *
   * @return \Illuminate\Http\Response
   */
  public function merge_dup_choirs_manual()
  {

    ini_set('max_execution_time', 3600);

    $group_by = isset($_GET['group_by']) ? strtolower($_GET['group_by']) : null;
    $choirs_merged_info = array();
    $choirs_grouped = array();
    $dup_count = 0;
    $has_duplicates = false;

    if(isset($_POST['duplicates'])){

      $duplicates = $_POST['duplicates'];

      foreach($duplicates as $group){

        $info = new \stdClass();

        $info->id = null;
        $info->choir_list = array();
        $info->name = null;
        $info->school_id = null;
        $info->director_ids = array();
        $info->choreographer_ids = array();
        $info->performers = array();
        $info->division_ids = array();
        $info->scheduleItems = array();
        $info->round_ids = array();
        $info->penalty_ids = array();
        $info->comments = array();
        $info->standing_ids = array();
        $info->updated_at = '0000-00-00 00:00:00';

        foreach($group as $id){

          $record = Choir::with('school', 'directors', 'choreographers', 'performers', 'divisions', 'scheduleItems', 'rounds', 'penalties', 'comments', 'standings')->find($id);

          // Get the first person ID or else the person ID that is already associated with a user account.
          if(null === $info->id){
            $info->id = $record->id;
            $choir = $record;
          }

          $info->choir_list[] = $record->id;

          // Get the first or most recently updated name.
          if(null === $info->name || ($record->name && $record->updated_at > $info->updated_at)){
            $info->name = trim($record->name);
          }

          if(null === $info->school_id || ($record->school_id && $record->updated_at > $info->updated_at)){
            $info->school_id = $record->school_id;
          }

          foreach($record->directors as $director){
            $info->director_ids[] = $director->id;
          }

          foreach($record->choreographers as $choreographer){
            $info->choreographer_ids[] = $choreographer->id;
          }

          foreach($record->performers as $performer){
            $info->performers[] = $performer;
          }

          foreach($record->divisions as $division){
            $info->division_ids[] = $division->id;
          }

          foreach($record->scheduleItems as $scheduleItem){
            $info->scheduleItems[] = $scheduleItem;
          }

          foreach($record->rounds as $round){
            $info->round_ids[] = $round->id;
          }

          foreach($record->penalties as $penalty){
            $info->penalty_ids[] = $penalty->id;
          }

          foreach($record->comments as $comment){
            $info->comments[] = $comment;
          }

          foreach($record->standings as $standing){
            $info->standing_ids[$standing->id] = array(
              'raw_rank' => $standing->pivot->raw_rank,
              'final_rank' => $standing->pivot->final_rank
            );
          }

          // Note the timestamp of the most recent record update.
          $info->updated_at = ($record->updated_at > $info->updated_at) ? $record->updated_at : $info->updated_at;

        }

        //dd($info);

        // Begin merging data.

        $choir->name = $info->name;
        $choir->school_id = $info->school_id;
        $choir->save();

        $info->director_ids = array_unique($info->director_ids);
        $choir->directors()->sync($info->director_ids);

        $info->choreographer_ids = array_unique($info->choreographer_ids);
        $choir->choreographers()->sync($info->choreographer_ids);

        foreach($info->performers as $performer){
          $performer->choir_id = $choir->id;
          $performer->save();
          foreach($performer->comments as $performer_comment){
            $performer_comment->choir_id = $choir->id;
            $performer_comment->save();
          }
        }

        $info->division_ids = array_unique($info->division_ids);
        $choir->divisions()->sync($info->division_ids);

        foreach($info->scheduleItems as $scheduleItem){
          $scheduleItem->choir_id = $choir->id;
          $scheduleItem->save();
        }

        $info->round_ids = array_unique($info->round_ids);
        $choir->rounds()->sync($info->round_ids);

        $info->penalty_ids = array_unique($info->penalty_ids);
        $choir->penalties()->sync($info->penalty_ids);

        foreach($info->comments as $comment){
          $comment->choir_id = $choir->id;
          $comment->recipient_id = $choir->id;
          $comment->save();
        }

        $choir->standings()->sync($info->standing_ids);

        // Delete the duplicate choirs.
        foreach($group as $id){
          if($info->id !== intval($id)){

            $duplicate_choir = Choir::find($id);

            $duplicate_choir->directors()->sync([]);
            $duplicate_choir->choreographers()->sync([]);
            $duplicate_choir->divisions()->sync([]);
            $duplicate_choir->rounds()->sync([]);
            $duplicate_choir->penalties()->sync([]);
            $duplicate_choir->standings()->sync([]);

            $duplicate_choir->delete();

          }
        }

        $choirs_merged_info[] = $info;

      }

      //dd($choirs_merged_info);

    }

    if(!empty($group_by)){

      $choirs = Choir::with('school', 'directors')->orderBy('id', 'asc')->get();

      if($group_by === 'name' || $group_by === 'school' || $group_by === 'both'){

        foreach($choirs as $choir){

          if($group_by === 'name'){
            $group_by_key = $choir->name;
          }

          if($group_by === 'school'){
            $school_id = is_null($choir->school_id) ? 'null' : $choir->school_id;
            $group_by_key = $school_id;
          }

          if($group_by === 'both'){
            $school_id = is_null($choir->school_id) ? 'null' : $choir->school_id;
            $group_by_key = $choir->name . $school_id;
          }

          $key = base64_encode($group_by_key);

          foreach($choirs_grouped as $existing_key => $group){
            foreach($group as $comp_choir){

              if($group_by === 'school' && $choir->school_id === $comp_choir->school_id){
                $key = $existing_key;
                break 2;
              }

              $diff = levenshtein($choir->name, $comp_choir->name);

              if($group_by === 'name'){
                if($diff < 3){
                  $key = $existing_key;
                  break 2;
                }
              }

              if($group_by === 'both'){
                if($diff < 3  && $choir->school_id === $comp_choir->school_id){
                  $key = $existing_key;
                  break 2;
                }
              }

            }
          }

          $choirs_grouped[$key][] = $choir;

        }

        foreach($choirs_grouped as $group){
          if(count($group) > 1){
            $has_duplicates = true;
            $dup_count++;
          }
        }

      }

    }

    return view('dedup.merge_dup_choirs_manual', compact('choirs_grouped', 'choirs_merged_info', 'dup_count', 'has_duplicates'));
  }

}
