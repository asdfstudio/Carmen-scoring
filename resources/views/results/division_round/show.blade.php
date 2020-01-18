@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.division.show-public', $division) !!}
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
  
	<h2>{{ $round->name}}</h2>

	@if($show_links)
		<div class="alert alert-info">
			<h3>Participants, You can view full scores</h3>
			<p>Click on the name of a <strong>choir</strong> or <strong>judge</strong> to view their score details.</p>
		</div>
	@endif
  
  <p class="scoring-method-note">This division uses the <strong>{{ $division->scoringMethod->name }}</strong> method of scoring. <a href="https://carmenscoring.com/scoring-methods" target="blank">View scoring method explanations</a>.</p>
  
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
  @if($division->scoring_method_id === 3 || $division->scoring_method_id === 4)
  	@include('scores.public.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges])
  @endif

  @include('scores.public.composite',['choirs' => $choirs, 'judges' => $judges, 'scoreboard' => $scoreboard])

@endsection
