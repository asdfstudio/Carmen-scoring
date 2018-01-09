@extends('layouts.simple')


@section('content-header')
	<h1>
		@if ($genderName)
			{{ $genderName }}
		@endif
		{{ $soloDivision->name }} Results
	</h1>

	<ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

	<ul class="actions-group mv">
		<li>{{ link_to_route('organizer.competition.solo-division.results','Overall results',[$competition, $soloDivision],['class' => 'action']) }}</li>
		<li>{{ link_to_route('organizer.competition.solo-division.results.female','Female results',[$competition, $soloDivision],['class' => 'action']) }}</li>
		<li>{{ link_to_route('organizer.competition.solo-division.results.male','Male results',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>

  @if ($soloDivision->performers->count() > 0)
    @include('performer.organizer.table', ['performers' => $soloDivision->performers, 'judges' => $soloDivision->judges])
  @endif

@endsection
