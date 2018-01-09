@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>{{ $schedule->name }}</h1>

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

        @if($item->round)
          <span class="division-name">{{ $item->round->division->name }}</span>
          <span class="round-name">{{ $item->round->name }}</span>
        @endif


        @if($item->choir)
          <span class="choir-name">{{ $item->choir->full_name }}</span>
        @elseif(!$item->name)
          <span class="choir-name tbd">TBD</span>
        @endif
      </li>
    @endforeach
  </ul>


@endsection
