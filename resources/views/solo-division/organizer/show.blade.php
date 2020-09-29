@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.show','Back to Competition',[$competition],['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.solo-division.edit','Edit Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.solo-division.manage','Manage Performers',[$competition, $soloDivision],['class' => 'action']) }}</li>

    @if($competition->organization->vote_setting)
    <li>{{ link_to_route('organizer.competition.solo-division.audience-votes','Audience Vote',[$competition, $soloDivision],['class' => 'action']) }}</li>
    @endif

    <li>{{ link_to_route('organizer.competition.solo-division.results','View Results',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')
		<ul class="list-group">
      <li class="list-group-item">Status: {{ $soloDivision->status }}</li>
      <li class="list-group-item">Max Performers: {{ $soloDivision->max_performers }}</li>
      <li class="list-group-item">Scoring Sheet: {{ $soloDivision->sheet->name }}</li>
			<li class="list-group-item">Category #1 Name: {{ $soloDivision->category_1 }}</li>
			<li class="list-group-item">Category #2 Name: {{ $soloDivision->category_2 }}</li>

			@if ($soloDivision->status_slug == 'finalized')
				<li class="list-group-item">Results URL: {{ link_to_route('results.solo-division.show', null, [$soloDivision, $soloDivision->access_code], ['target' => '_blank']) }}</li>
			@endif
    </ul>

		<ul class="actions-group mv">
		@can('activateScoring', $soloDivision)
			<li>{!! form($activateScoringForm) !!}</li>
		@endcan

		@can('completeScoring', $soloDivision)
			<li>{!! form($completeScoringForm) !!}</li>

		@endcan

		@can('finalizeScoring', $soloDivision)
			<li>{!! form($finalizeScoringForm) !!}</li>

		@endcan
		</ul>

    <h2>Judges</h2>

    @if ($soloDivision->judges->count() > 0)
      <ul class="list-group">
        @foreach ($soloDivision->judges as $judge)
          <li class="list-group-item">{{ $judge->full_name }}</li>
        @endforeach
      </ul>
    @else
      <p>There are no judges set up for this solo division.</p>
    @endif



    <h2>Performers</h2>

    @if ($soloDivision->performers->count() > 0)
      @include('performer.organizer.table', ['performers' => $soloDivision->performers])
    @else
      <p>There are no performers set up for this solo division.</p>
    @endif

@endsection
