@extends('layouts.recording_simple')

@section('content-header')
  <h1>My Scores - Summary View</h1>

  {{ link_to_route('judge.round.scores.spreadsheet', 'Go to Spreadsheet View', [$competition, $division, $round], ['class' => 'action'])}}
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

  @if($round->status_slug == 'completed')
    <ul class="actions-group mv-2">
      <li>{{ link_to_route('judge.round.scores', "View All Judges' Scores", [$round->division->competition, $round->division, $round], ['class' => 'action']) }}</li>
    </ul>
  @endif

  @if($round->targets->count() > 0)
    <div class="alert alert-info">
      <h4>This round feeds into <strong>{{ $round->targets->first()->division->name }}, {{ $round->targets->first()->name }}</strong>  along with {{ $round->targets->first()->sources->count() }} other round(s).</h4>
      <p>{{ link_to_route('judge.round.scores.sources', 'View a combined spreadsheet', [$round->division->competition_id, $round->targets->first()->division, $round->targets->first()], ['class' => 'btn btn-primary']) }}&nbsp; showing your scores for all of these rounds together.</p>
    </div>
  @endif

  @include('scores.choirs_judge_aggregate',[
    'choirs' => $round->choirs,
    'division' => $round->division,
    'judge' => $round->division->judges->first()
  ])
  @include('scores.record_file',[
    'choirs' => $round->choirs,
    'division' => $round->division,
    'judge' => $round->division->judges->first()
  ])
@endsection

