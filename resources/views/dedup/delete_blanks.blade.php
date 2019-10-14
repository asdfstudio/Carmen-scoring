@extends('layouts.simple')

@section('content-header')
  <h1>Delete Blanks</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  @if($blank_count)
    <p>
      {{ $blank_count }} blank record(s) have been deleted from the <code>people</code> table in
      the database. No further action is necessary.
    </p>
  @else
    <p>There are no blank records in the <code>people</code> table in the database.</p>
  @endif

@endsection