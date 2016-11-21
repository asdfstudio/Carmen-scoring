@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.round.show',$round->division->competition,$round->division,$round) !!}
@endsection

@section('content-header')
	<h1>{{ $round->name }}</h1>

	<ul class="actions-group">

		@can('showAll','App\Round')
			<li>{{ link_to_route('organizer.competition.division.round.index', 'Back to all Rounds', [$division->competition,$division], ['class' => 'action']) }}</li>
		@endcan

		@can('update', $round)
			<li>
				{{ link_to_route('organizer.competition.division.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action']) }}
			</li>
		@endcan

		@can('activateScoring', $round)
			<li>
				{!! form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

		@can('deactivateScoring', $round)
			<li>
				{!! form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

		@can('completeScoring', $round)
			<li>
				{!! form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

		@can('reactivateScoring', $round)
			<li>
				{!! form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

	</ul>
@endsection

@section('content')

	@parent

	<ul class="list-group horizontal">
		<li class="list-group-item">
			<?php $active = $division->scoringMethod->slug == 'ranked' ? 'active division-scoring-method' : false; ?>
			<a class="score-view-toggle {{ $active }}" href="#rankings" data-score-view="rank">Rankings</a>

			@if($active)
				<span>(division scoring method)</span>
			@endif
		</li>
		<li class="list-group-item">
			<?php $active = $division->scoringMethod->slug == 'raw' ? 'active division-scoring-method' : false; ?>
			<a class="score-view-toggle {{ $active }}" href="#weighted" data-score-view="weighted">Weighted</a>

			@if($active)
				<span>(division scoring method, {{ $division->captionWeighting->name }})</span>
			@else
				<span>({{ $division->captionWeighting->name }})</span>
			@endif

		</li>
		<li class="list-group-item">
			<a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
		</li>
	</ul>

	@include('scores.organizer.composite',['choirs' => $division->choirs, 'judges' => $division->judges])

  </div>

@endsection
