@extends('layouts.simple')

@section('content-header')
  <h1>{{ $judge->full_name }}</h1>

  <ul class="actions-group">
    <li>
      {{ link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action']) }}
    </li>
  </ul>
@endsection

@section('content')
		<h2>Choose captions for {{ $judge->first_name }} to score</h2>
		{!! form($form) !!}

    <h2>Remove {{ $judge->first_name }} from this division</h2>

    {!! form($deleteForm) !!}

@endsection
