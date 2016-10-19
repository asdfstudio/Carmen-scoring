@extends('layouts.simple')


@section('division_navigation_bar')

@endsection


@section('round_navigation_bar')
  @if (isset($round) AND isset($division->rounds))
    <div class="round-navigation-bar body-width">
      <ul class="round-navigation">
        @foreach ($division->rounds as $rd)
          <?php $active_class = $rd->id == $round->id ? 'active' : '';?>
          <li>
            <a href="{{ route('judge.round.scores.summary', [$competition, $division, $rd]) }}" class="{{ $active_class }}">
              {{ $rd->name }}
              <span class="round-navigation-link-status">{{ $rd->status() }}</span>
            </a>
          </li>
        @endforeach
      </ul>
      <div class="round-status">
        Status: {{ $round->status() }}
      </div>
    </div>
  @endif
@endsection


@section('content')

  @include('alert.all')

  @include('scores.choirs_judge_aggregate',[
    'choirs' => $round->division->choirs,
    'division' => $round->division,
    'judge' => $round->division->judges->first()
  ])

@endsection
