@extends('layouts.simple')

@section('content-header')
  <h1>Add a judge to this division</h1>
  <ul class="actions-group">
    <li>
      {{ link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action']) }}
    </li>
  </ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection