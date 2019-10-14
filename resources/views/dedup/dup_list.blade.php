@extends('layouts.simple')

@section('content-header')
  <h1>List Duplicate People</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')

    <p>
      This page shows duplicate records, using either the primary email or the name of the person to
      find duplicates. If you are grouping duplicates by name, the script will group names that are
      very similar, with only 1 to 3 characters different. This helps spot records that are duplicated
      because of a typo, but it may also result in false positives that can be ignored.
    </p>

    <p style="margin: 20px 0;">
        <a href="{{ url()->current() }}?group_by=email" class="btn btn-primary">Group By Email</a>
        <a href="{{ url()->current() }}?group_by=name" class="btn btn-primary">Group By Name</a>
        <a href="{{ url()->current() }}" class="btn btn-default">Clear</a>
    </p>
    
    @if($group_by && !$has_duplicates)
      <hr>
      <p>There are {{ count($people_grouped) }} people in the database with no duplicates based on {{ $group_by }}.</p>
    @endif

    @if($group_by && $has_duplicates)
      <hr>
      <p><strong>There are {{ $dup_count }} people with potential duplicates based on {{ $group_by }}.</strong></p>
      <hr>
      <ul class="list-group">
        @foreach($people_grouped as $group)
          @if(count($group) > 1)
            <li class="list-group-item">
              {{ $group[0]->first_name }} {{ $group[0]->last_name }} ({{ $group[0]->email }}) appears {{ count($group) }} times:
              <table style="width: 100%; margin-top: 10px;">
                <thead>
                  <tr>
                    <th style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Person Entries</th>
                    <th style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Associated Info</th></tr>
                </thead>
                <tbody>
                  @foreach($group as $person)
                    <tr>
                      <td style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                        {{ $person->first_name }} {{ $person->last_name }}<br>
                        Person ID: {{ $person->id }}
                      </td>
                      <td style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                        <ul>
                          <li>
                            Email(s):
                            @if($person->emails_additional)
                              {{ $person->email }}, {{ $person->emails_additional }}
                            @else
                              {{ $person->email }}
                            @endif
                          </li>
                          <li>Phone: {{ $person->tel }}</li>
                          <li>Type(s): {{ implode(', ', $person->typeNames()) }}</li>
                          <li>Choir(s): {{ implode(', ', $person->choirIds()) }}</li>
                          <li>Schools(s): {{ implode(', ', $person->schoolIds()) }}</li>
                        @if(isset($person->user))
                          <li>User Account:
                            <ul>
                              <li>User ID: {{ $person->user->id }}</li>
                              <li>Username: {{ $person->user->username }}</li>
                              <li>User Email: {{ $person->user->email }}</li>
                            </ul>
                          </li>
                        @endif
                        </ul>
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