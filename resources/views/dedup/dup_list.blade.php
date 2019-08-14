@extends('layouts.simple')

@section('content-header')
  <h1>List Duplicate People</h1>
  <a href="{{ route('admin.dedup') }}" class="action">Back</a>
@endsection


@section('content')

    @if(!$has_duplicates)
      <p>There are {{ count($items) }} people in the database with no duplicates.</p>
    @endif

    @if($has_duplicates)
    <ul class="list-group">
      @foreach($items as $item)
        @if(count($item) > 1)
          <li class="list-group-item">
            {{ $item[0]->first_name }} {{ $item[0]->last_name }} ({{ $item[0]->email }}) appears {{ count($item) }} times:
            <table style="width: 100%; margin-top: 10px;">
              <thead>
                <tr>
                  <th style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Person Entries</th>
                  <th style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Associated Info</th></tr>
              </thead>
              <tbody>
                @foreach($item as $person)
                  <tr>
                    <td style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                      {{ $person->id }}: {{ $person->first_name }} {{ $person->last_name }}, {{ $person->person_type }}
                    </td>
                    <td style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                      @if(isset($person->user))
                        <div>User: {{ $person->user->id }}, {{ $person->user->username }}, {{ $person->user->email }} (Person ID: {{ $person->user->person_id }})</div>
                      @endif
                      @if(!empty($person->subject))
                        <div>Choir: {{ $person->subject->id }}, {{ $person->subject->name }} (School ID: {{ $person->subject->school_id }})</div>
                      @endif
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </li>
        @endif
      @endforeach
    </ul>
    @endif

@endsection