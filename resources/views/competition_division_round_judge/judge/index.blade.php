@extends('layouts.simple')

@section('content-header')
  <h1>All Scores - Summmary View</h1>

  {{ link_to_route('judge.round.scores.summary', 'Go to My Scores', [$competition, $division, $round], ['class' => 'action'])}}
@endsection


@section('division_navigation_bar')

@endsection


@section('round_navigation_bar')
  @if (isset($round) AND isset($division->rounds))
    <div class="round-navigation-bar body-width">
      <ul class="round-navigation">
        @foreach ($division->rounds as $rd)
          <?php $active_class = $rd->id == $round->id ? 'active' : '';?>
          <li class="round-{{ $rd->status_slug }}">
            <a href="{{ route('judge.round.scores.summary', [$competition, $division, $rd]) }}" class="{{ $active_class }}">
              {{ $rd->name }}

              {!! $rd->status_label('round-navigation-link-status') !!}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  @endif
@endsection


@section('content')

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

  @include('scores.judge.composite',['choirs' => $division->choirs])


@endsection
