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

  {{-- Raw Scoring, 50/50 --}}
  @if ($division->scoring_method_id === 1 && $division->caption_weighting_id === 2)
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  @endif
  
  {{-- Raw Scoring, 60/40 --}}
  @if ($division->scoring_method_id === 1 && $division->caption_weighting_id === 1)
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>(division scoring method, {{ $division->captionWeighting->name }})</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  @endif
  
  {{-- Ranked Scoring, 50/50 --}}
  @if ($division->scoring_method_id > 1 && $division->caption_weighting_id === 2)
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  @endif

  {{-- Ranked Scoring, 60/40 --}}
  @if ($division->scoring_method_id > 1 && $division->caption_weighting_id === 1)
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>({{ $division->captionWeighting->name }})</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  @endif
  
  {{-- Condorcet methods have an extra table that is formatted a little differently to show rankings. --}}
  @if($division->scoring_method_id === 3 || $division->scoring_method_id === 4)
  	@include('scores.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges])
  @endif

	@include('scores.organizer.composite',['choirs' => $choirs, 'judges' => $judges])

  </div>

@endsection
