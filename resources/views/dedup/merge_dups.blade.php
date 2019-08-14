@extends('layouts.simple')

@section('content-header')
  <h1>Merge Duplicates</h1>
  <a href="{{ route('admin.dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  <p>
    This script checks for dubplicates in the 'people' table of the database and
    merges them into a single record for each person. Data such as their role as a
    choir director, choir choreographer, and judge will be preserved.
  </p>

  <p>It is safe to run this script more than once. It will NOT harm data that has already been merged.</p>

  <p>Use the "Dry Run" button to preview the changes without actually modifying the database. Use the "Merge" button to do the conversion.</p>

  <p style="margin: 20px 0;">
    @if(!$run)
      <script>
        function disableButtons(){
          var b = document.getElementsByClassName('run-button');
          for(var i=0; i<b.length; i++){
            b[i].setAttribute('disabled', 'disabled');
          }
        }
      </script>
      <a href="{{ url()->current() }}?run" class="run-button btn btn-primary" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Merge</a>
      <a href="{{ url()->current() }}?dryrun" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Dry Run</a>
    @endif
    @if($run || $dryrun)
      <a href="{{ url()->current() }}" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Clear</a>
    @endif
  </p>

  @if($run_dryrun_error)
    <div class="alert alert-danger">This script cannot be set to "run" and "dryrun" at the same time.</div>
  @endif

  @if(($run || $dryrun) && !$run_dryrun_error)

      <hr>

      <h3>Duplicates ({{ count($people) }} records for {{ count($people_merged_info) }} individuals)</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">
        
        @foreach($people_merged_info as $person)
          <div style="padding: 20px;">
            <p>{{ $person->full_name }} ({{ $person->email }})</p>
            <ul>
              <li>{{ count($person->people_list) }} record(s) in the database</li>
              <li>Person IDs: {{ implode(', ', $person->people_list) }}</li>
              <li>Person ID after merge: {{ $person->id }}</li>
              @if($person->user_id)
                <li>User ID: {{ $person->user_id }}</li>
              @else
                <li>No user account associated with this person</li>
              @endif
              @if($person->divisions_judged)
                <li>Judge of {{ count($person->divisions_judged) }} division(s) and {{ count($person->divisions_pivot_captions) }} caption(s) with {{ count($person->comments) }} comment(s)</li>
              @endif
              @if($person->choirs_directed)
                <li>Director of {{ count($person->choirs_directed) }} choirs(s)</li>
              @endif
              @if($person->choirs_choreographed)
                <li>Choreographer of {{ count($person->choirs_choreographed) }} choirs(s)</li>
              @endif
            </ul>
            @if(isset($person->run_messages) && !empty($person->run_messages))
              <div class="alert alert-info">
                @foreach($person->run_messages as $message)
                  {!! $message !!}<br>
                @endforeach
              </div>
            @endif
          </div>

          <hr>

        @endforeach
        
      </div>


  @endif

@endsection
