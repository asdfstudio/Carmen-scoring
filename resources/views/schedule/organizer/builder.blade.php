@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>Build Your Schedule: {{ $schedule->name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}</li>
	</ul>

@endsection

@section('content')

  <p>Build your schedule by dragging choirs to the schedule.</p>

  <div class="well">
    <label for="">First Performance</label>
    <input type="text" class="timepicker-options" name="first_performance" placeholder="First performance" value="8:00">

    <label for="">Last Performance</label>
    <input type="text" class="timepicker-options" name="last_performance" placeholder="Last performance" value="22:00">

    <label for="">Time interval in minutes</label>
    <input type="text" class="timepicker-options" name="step" placeholder="Time increment" value="30">
  </div>



  <div class="schedule-builder-container">
    <div class="schedule-builder">
      <div class="schedule-builder-header">
        Schedule
      </div>
      <ul class="schedule-builder-list schedule ui-droppable">
        <li class="spacer top"></li>
        @foreach($schedule->items as $item)
            <li class="schedule-item choir ui-draggable" id="item_{{ $item->division_id}}_{{ $item->choir_id }}" data-division-id="{{ $item->division_id }}" data-choir-id="{{ $item->choir_id }}">
            <span class="sort-handle ui-draggable-handle"><i class="fa fa-sort"></i></span>
            <input type="text" class="scheduled_time" value="{{ $item->scheduled_time }}">

            @if ($item->name)
              <input type="text" class="item_name" value="{{ $item->name }}">
            @endif

            @if ($item->division)
                <span class="division-name">{{ $item->division->name }}</span>
                <span class="round-name">{{ $item->division->round->name }}</span>
            @endif

            @if($item->choir)
              <span class="choir-name">{{ $item->choir->name }}</span>
            @elseif(!$item->name)
              <span class="choir-name tbd">TBD</span>
            @endif
          </li>
        @endforeach
        <li class="spacer bottom"></li>
      </ul>
    </div>


    <div class="schedule-builder">
      <div class="schedule-builder-header">
        Choirs
      </div>
      <ul class="schedule-builder-list schedule-items divisions">

        <li class="division">
          <span class="division-heading">Placeholders / Non-Performances</span>
          <ul class="choirs non-performance-items">
            @php $i = 0; @endphp
            @while ($i < 10)
              <li class="schedule-item">
                <input type="text" class="scheduled_time" value="">
                <input type="text" class="item_name" value="" placeholder="Lunch, Break, etc.">
              </li>
              @php $i++; @endphp
            @endwhile

          </ul>
        </li>

        @foreach($competition->rounds as $rnd)
          <li class="division">
            <span class="division-heading">{{ $rnd->name }}</span>
            <ul class="rounds">
              @foreach($rnd->divisions as $div)
                <li class="round">
                  <span class="round-heading">{{ $div->name }}</span>
                  <ul class="choirs">
                    @foreach($div->choirs as $choir)
                      @php
                      $isInSchedule = $schedule->items->where('division_id', $div->id)->where('choir_id', $choir->id)->count();

                      $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('choir_id', $choir->id)->count();
                      @endphp
                      @if(!$isInSchedule AND !$isInAnotherSchedule)
                        <li class="schedule-item choir" id="item_{{ $div->id }}_{{ $choir->id }}" data-division-id="{{ $div->id }}" data-choir-id="{{ $choir->id }}">
                          <input type="text" class="scheduled_time" value="">
                          <span class="division-name">{{ $rnd->name }}</span>
                          <span class="round-name">{{ $div->name }} </span>
                          <span class="choir-name">{{ $choir->full_name }}</span>
                        </li>
                      @endif
                    @endforeach

                    @php
                    if($div->max_choirs > 0 AND $div->max_choirs != $div->choirs->count())
                    {
                      $tbdChoirsCount = $div->max_choirs - $div->choirs->count();

                      $isInScheduleCount = $schedule->items->where('division_id', $div->id)->where('choir_id', 0)->count();

                      $tbdChoirsCount = $tbdChoirsCount - $isInScheduleCount;
                    }
                    else {
                      $tbdChoirsCount = 0;
                    }
                    @endphp

                    @if($tbdChoirsCount > 0)
                      @php $i = 0; @endphp
                      @while ($i < $tbdChoirsCount)
                        <li class="schedule-item choir" data-division-id="{{ $div->id }}" data-choir-id="">
                          <span class="division-name">{{ $rnd->name }}</span>
                          <span class="round-name">{{ $div->name }} </span>
                          <span class="choir-name choir-tbd">TBD</span>
                        </li>
                        @php $i++; @endphp
                      @endwhile
                    @endif
                  </ul>
                </li>
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    </div>


    <div class="schedule-builder-footer">
      <a href="{{ route('organizer.competition.schedule.builder.store', [$competition->id, $schedule->id]) }}" class="save-schedule-btn btn btn-primary">Save Schedule</a>

      <span class="schedule-builder-status-message"></span>

      <span class="is-dirty-message">
        Your schedule has changed. You must click "Save Schedule" to complete your changes.
      </span>
    </div>
  </div>


@endsection


@section('body-footer')

  <script src="/dist/js/schedule-builder.js"></script>
  <script src="/dist/js/vendor/jquery.timepicker.min.js"></script>
  <script>
    $( function() {

      ScheduleBuilder.init();

      $('.save-schedule-btn').on('click', function(event) {
        event.preventDefault();
        ScheduleBuilder.save($(this).attr('href'));
      });

      $('ul.schedule').on('sortupdate', function(event, ui) {

      });

      $('input.scheduled_time').timepicker({
        'timeFormat': 'h:i a',
        'minTime': '8:00',
        'maxTime': '22:00',
        'step': 30
      });

      $('input.timepicker-options').not('input[name="step"]').timepicker({
        'timeFormat': 'h:i a'
      });

      $('input.timepicker-options').on('change', function(event) {
        event.preventDefault();
        var firstPerformance = $('input[name="first_performance"]').val();
        var lastPerformance = $('input[name="last_performance"]').val();
        var step = $('input[name="step"]').val();
        console.log('ready or change');

        $('input.scheduled_time').timepicker('option', {
          'minTime': firstPerformance,
          'maxTime': lastPerformance,
          'step': step
        });
      });
    });
  </script>
@endsection
