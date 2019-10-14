@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.round.show',$round->division->competition,$round->division,$round) !!}
@endsection

@section('content-header')
	<h1>{{ $round->division->name }}, {{ $round->name }} Sources</h1>

	<ul class="actions-group">

		@can('showAll','App\Round')
			<li>{{ link_to_route('organizer.competition.division.round.index', 'Back to all Rounds', [$division->competition,$division], ['class' => 'action']) }}</li>
		@endcan

	</ul>
@endsection

@section('content')

	@parent

	<ul class="list-group horizontal">
		<li class="list-group-item">
			@php $active = $division->scoringMethod->slug == 'ranked' ? 'active division-scoring-method' : false; @endphp
			<a class="score-view-toggle {{ $active }}" href="#rankings" data-score-view="rank">Rankings</a>

			@if($active)
				<span>(division scoring method)</span>
			@endif
		</li>
		<li class="list-group-item">
			@php $active = $division->scoringMethod->slug == 'raw' ? 'active division-scoring-method' : false; @endphp
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


	@include('scores.organizer.composite',['choirs' => $choirs, 'judges' => $judges])

  </div>

@endsection
