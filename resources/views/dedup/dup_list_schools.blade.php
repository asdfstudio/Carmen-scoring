@extends('layouts.simple')

@section('content-header')
  <h1>List Duplicate Schools</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')

    <p>
      This page shows duplicate school records, based on name and location.
    </p>

    @if(!$has_duplicates)
      <hr>
      <p>There are {{ count($schools_grouped) }} schools in the database with no duplicates.</p>
    @endif

    @if($has_duplicates)
      <hr>
      <p><strong>There are {{ $dup_count }} schools with potential duplicates.</strong></p>
      <hr>
      <ul class="list-group">
        @foreach($schools_grouped as $group)
          @if(count($group) > 1)
            <li class="list-group-item">
              {{ $group[0]->name }} appears {{ count($group) }} times:
              <ul>
                @foreach($group as $school)
                  <li>
                    ID: {{ $school->id }}<br>
                    Name: {{ $school->name }}<br>
                    Choirs:
                      @foreach($school->choirs as $i => $choir)
                        @if($i < count($school->choirs)-1)
                          {{ $choir->id }}, 
                        @else
                          {{ $choir->id }}
                        @endif
                      @endforeach
                    <br>
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
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach
      </ul>
    @endif


@endsection