@extends('layouts.simple')

@section('content-header')
  <h1>Merge Duplicates Manually</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  <p>
    This script finds duplicate names (or very similar names that may just be differentiated by a typo)
    and allows you to manually merge them if you confirm that they are the same person.
  </p>
  
  <p>
    On each row, check the box for the records that should be merged. Leave records unchecked if they don't
    need to be merged with the others on that row.  When you are done making selections, click the "Merge
    Selected" button at the bottom of the page.
  </p>
  
  <hr>
  
  @if(!empty($people_merged_info))
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        @foreach($people_merged_info as $info)
          <li>{{ $info->full_name }} &mdash; IDs: {{ implode(', ', $info->people_list) }} have been merged into {{ $info->id }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <h3>{{ $potential_dup_count }} Potential Duplicates</h3>
  
  <form method="post">
    <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

      @foreach($people_grouped as $key => $group)
        @if(count($group) > 1)
          <div style="display: flex;">
            @foreach($group as $person)
              <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                <label style="white-space: pre"><input type="checkbox" name="duplicates[{{ $key }}][]" value="{{ $person->id }}">  {{ $person->first_name }} {{ $person->last_name }} (ID: {{ $person->id }})</label>
                <ul>
                  <li>{{ $person->email }}</li>
                  <li>{{ $person->tel }}</li>
                  <li>Types: {{ implode(', ', $person->typeNames()) }}</li>
                  <li>Choirs: {{ implode(', ', $person->choirIds()) }}</li>
                  <li>Schools: {{ implode(', ', $person->schoolIds()) }}</li>
                </ul>
              </div>
            @endforeach
          </div>

          <hr>

        @endif
      @endforeach

    </div>
    {{ csrf_field() }}
    <button name="merge" class="run-button btn btn-primary">Merge Selected</button>
  </form>

@endsection