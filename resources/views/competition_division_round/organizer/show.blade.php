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

		@can('reactivateScoring', $round)
      <li>
        {!! form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
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
	</ul>
@endsection

@section('content')

  @php
    if($division->scoring_method_id === 3 || $division->scoring_method_id === 4){
      $rankings_tab_name = "Condorcet";
      $rankings_class = "condorcet";
      $is_condorcet = true;
      $show_borda = true;
    } else {
      $rankings_tab_name = "Rankings";
      $rankings_class = "rank";
      $is_condorcet = false;
      $show_borda = false;
    }
  @endphp

	@parent

	@if ($round->isMissingScores())
		<p class="alert alert-warning">This round is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	@endif

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
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="{{ $rankings_class }}">{{ $rankings_tab_name }}</a>
        <span>(division scoring method)</span>
      </li>
    @if ($show_borda)
      <li class="list-group-item">
        <a class="score-view-toggle" href="#rankings" data-score-view="rank">Borda Count</a>
      </li>
    @endif
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  @endif

  {{-- Ranked Scoring, 60/40 --}}
  @if ($division->scoring_method_id > 1 && $division->caption_weighting_id === 1)
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="{{ $rankings_class }}">{{ $rankings_tab_name }}</a>
        <span>(division scoring method)</span>
      </li>
    @if ($show_borda)
      <li class="list-group-item">
        <a class="score-view-toggle" href="#rankings" data-score-view="rank">Borda Count</a>
      </li>
    @endif
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
  @if($is_condorcet)
  	@include('scores.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges])
  @endif

	@include('scores.organizer.composite',['choirs' => $choirs, 'judges' => $division->judges])

  </div>

@endsection
