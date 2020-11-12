@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
    @php
        $schedule_day = $schedule->items->count() > 0 ? $schedule->items->first()->scheduled_time : FALSE;
        if ($schedule_day) {
            $schedule_day = \Carbon\Carbon::parse($schedule_day);
            // Only display the date if it's not the default
            $dateString = $schedule_day->year > 1000 ? $schedule_day->format('m/d/Y') : FALSE;
        }
    @endphp
    <h1>{{ $schedule->name }}@if($dateString) - {{ $dateString }}@endif</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.schedule.builder', 'Build Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{!! form($deleteForm) !!}</li>
	</ul>

@endsection

@section('content')

  <ul class="schedule-list">
    @foreach($schedule->items as $item)
      <li class="schedule-item choir" id="item_{{ $item->round_id }}_{{ $item->choir_id }}" data-round-id="{{ $item->round_id }}" data-choir-id="{{ $item->choir_id }}">

        @if($item->scheduled_time)
          <span class="scheduled-time">{{ \Carbon\Carbon::parse($item->scheduled_time)->format('g:i a') }}</span>
        @endif

        @if ($item->name)
          <span class="item-name">{{ $item->name }}</span>
        @endif

        @if($item->round->id)
          <span class="round-name">{{ $item->round->name }}</span>
        @endif

        @if($item->division->id)
          <span class="division-name">{{ $item->division->name }}</span>
        @endif

        @if($item->choir->id)
          <span class="choir-name">{{ $item->choir->full_name }}</span>
        @elseif(!$item->name)
          <span class="choir-name tbd">TBD</span>
        @endif
      </li>
    @endforeach
  </ul>


@endsection
