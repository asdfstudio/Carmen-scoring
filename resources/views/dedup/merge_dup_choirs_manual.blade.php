@extends('layouts.simple')

@section('content-header')
  <h1>Merge Duplicates Manually</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  <p>
    This script finds duplicate choirs based on name or school association. (Very similar names will be lumped together in case of typos.)
    Then you can manually merge them if you confirm that they are the same school.
  </p>
  
  <p style="margin: 20px 0;">
      <a href="{{ url()->current() }}?group_by=both" class="btn btn-primary">Group By Name &amp; School</a>
      <a href="{{ url()->current() }}?group_by=name" class="btn btn-primary">Group By Name</a>
      <a href="{{ url()->current() }}?group_by=school" class="btn btn-primary">Group By School</a>
      <a href="{{ url()->current() }}" class="btn btn-default">Clear</a>
  </p>
  
  <hr>
  
  @if(!empty($choirs_merged_info))
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        @foreach($choirs_merged_info as $info)
          <li>{{ $info->name }} &mdash; IDs: {{ implode(', ', $info->choir_list) }} have been merged into {{ $info->id }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(!empty($choirs_grouped))
    
    <h3>{{ $dup_count }} Potential Duplicates</h3>
    
    <p>
      On each row, check the box for the records that should be merged. Leave records unchecked if they don't
      need to be merged with the others on that row.  When you are done making selections, click the "Merge
      Selected" button at the bottom of the page.
    </p>
    
    <form method="post">
      <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

        @foreach($choirs_grouped as $key => $group)
          @if(count($group) > 1)
            <div style="display: flex;">
              @foreach($group as $choir)
                <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                  <label style="white-space: pre"><input type="checkbox" name="duplicates[{{ $key }}][]" value="{{ $choir->id }}">  {{ $choir->name }} (ID: {{ $choir->id }})</label>
                  <ul>
                    <li>School: {{ isset($choir->school) ? $choir->school->name . ' (' . $choir->school->id . ')' : 'None' }}</li>
                    <li>Directors: 
                      @foreach($choir->directors as $i => $director)
                        @if($i < count($choir->directors)-1)
                          {{ $director->first_name }} {{ $director->last_name }} ({{ $director->id }}), 
                        @else
                          {{ $director->first_name }} {{ $director->last_name }} ({{ $director->id }})
                        @endif
                      @endforeach
                    </li>
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
  @endif

@endsection