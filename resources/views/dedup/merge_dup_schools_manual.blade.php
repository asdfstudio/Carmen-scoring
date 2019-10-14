@extends('layouts.simple')

@section('content-header')
  <h1>Merge Duplicates Manually</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  <p>
    This script finds duplicate schools based on names or locations (or very similar names/locations that may be differentiated by a typo)
    and allows you to manually merge them if you confirm that they are the same school.
  </p>
  
  <p style="margin: 20px 0;">
      <a href="{{ url()->current() }}?group_by=both" class="btn btn-primary">Group By Name &amp; Location</a>
      <a href="{{ url()->current() }}?group_by=name" class="btn btn-primary">Group By Name</a>
      <a href="{{ url()->current() }}?group_by=location" class="btn btn-primary">Group By Location</a>
      <a href="{{ url()->current() }}" class="btn btn-default">Clear</a>
  </p>
  
  <hr>
  
  @if(!empty($schools_merged_info))
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        @foreach($schools_merged_info as $info)
          <li>{{ $info->name }} &mdash; IDs: {{ implode(', ', $info->school_list) }} have been merged into {{ $info->id }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(!empty($schools_grouped))
    
    <h3>{{ $dup_count }} Potential Duplicates</h3>
    
    <p>
      On each row, check the box for the records that should be merged. Leave records unchecked if they don't
      need to be merged with the others on that row.  When you are done making selections, click the "Merge
      Selected" button at the bottom of the page.
    </p>
    
    <form method="post">
      <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

        @foreach($schools_grouped as $key => $group)
          @if(count($group) > 1)
            <div style="display: flex;">
              @foreach($group as $school)
                <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                  <label style="white-space: pre"><input type="checkbox" name="duplicates[{{ $key }}][]" value="{{ $school->id }}">  {{ $school->name }} (ID: {{ $school->id }})</label>
                  <ul>
                    <li>Choirs: 
                      @foreach($school->choirs as $i => $choir)
                        @if($i < count($school->choirs)-1)
                          {{ $choir->id }}, 
                        @else
                          {{ $choir->id }}
                        @endif
                      @endforeach
                    </li>
                    <li>
                      Address:<br>
                      <address style="padding: 5px 0 0 10px; font-style: italic;">
                        @if(!empty($school->place->address))
                          {{ $school->place->address }}<br>
                        @endif
                        @if(!empty($school->place->address_2))
                          {{ $school->place->address2 }}<br>
                        @endif
                        @if(!empty($school->place->city_state()))
                          {{ $school->place->city_state() }}
                        @endif
                        @if(!empty($school->place->postal_code))
                          {{ $school->place->postal_code }}
                        @endif
                      </address>
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