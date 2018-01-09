@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

  <h2>{{ $performer->name }}, {{ $performer->choir->full_name }}</h2>

  @include('performer.organizer.view-scores')

@endsection
