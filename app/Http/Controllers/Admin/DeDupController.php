<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Person;
use App\Judge;
use App\Choir;

class DeDupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		  return view('dedup.index', compact('items'));
    }

    /**
     * Display a listing of the resource.
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
}
