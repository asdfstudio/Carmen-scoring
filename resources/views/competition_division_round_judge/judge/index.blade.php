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
          @php $active_class = $rd->id == $round->id ? 'active' : '';@endphp
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
  	@include('scores.judge.ranked_condorcet',['choirs' => $division->choirs, 'judges' => $division->judges])
  @endif

  @include('scores.judge.composite',['choirs' => $division->choirs])


@endsection
