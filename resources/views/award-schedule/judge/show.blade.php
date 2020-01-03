@extends('layouts.simple')

@section('content')

  <h1>{{ $schedule->name }}</h1>

  {{ link_to_route('judge.competition.schedule.index', 'Back to Schedules', [$competition]) }}

  <ul class="schedule-list">
    @foreach($schedule->items as $item)

      @php
      $isDivisionJudge = $judge->divisions()->where('division_id', $item->round->division_id)->count();
      @endphp
      <li class="schedule-item choir" id="item_{{ $item->round_id }}_{{ $item->choir_id }}" data-round-id="{{ $item->round_id }}" data-choir-id="{{ $item->choir_id }}">

        @if($isDivisionJudge)
          <a class="division-name" href="{{ route('judge.competition.division.show', [$competition, $item->round->division_id]) }}">{{ $item->round->division->name }}</a>
        @else
          <span class="division-name">{{ $item->round->division->name }}</span>
        @endif


        @if($isDivisionJudge)
          <a class="round-name" href="{{ route('judge.round.scores.summary', [$competition, $item->round->division_id, $item->round]) }}">{{ $item->round->name }}</a>
        @else
          <span class="round-name">{{ $item->round->name }}</span>
        @endif


        @if($item->choir)

          @if($isDivisionJudge)
            <a class="choir-name" href="{{ route('judge.competition.division.round.choir.show', [$competition, $item->round->division_id, $item->round, $item->choir]) }}">{{ $item->choir->name }}</a>
          @else
            <span class="choir-name">{{ $item->choir->full_name }}</span>
          @endif

        @else
          <span class="choir-name tbd">TBD</span>
        @endif
      </li>
    @endforeach
  </ul>


@endsection
