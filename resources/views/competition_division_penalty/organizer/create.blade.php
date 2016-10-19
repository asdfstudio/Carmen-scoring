@extends('layouts.simple')

@section('content-header')
  <h1>Create a penalty</h1>

  <ul class="actions-group">
    <li>
      {{ link_to_route('organizer.competition.division.penalty.index','Back to penalties', [$division->competition->id, $division->id], ['class' => 'action']) }}
    </li>
  </ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection
