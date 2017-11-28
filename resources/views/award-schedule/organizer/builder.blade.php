@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule) !!}
@endsection

@section('content')

  <h1>{{ $schedule->name }}</h1>

  <p>Build your schedule by dragging awards to the schedule.</p>

  <div class="schedule-builder-container">
    <div class="schedule-builder">
      <div class="schedule-builder-header">
        Award Ceremony Schedule
      </div>
      <ul class="schedule-builder-list schedule">
        @foreach($schedule->items as $item)
          <li class="schedule-item award" data-division-id="{{ $item->division_id }}" data-award-id="{{ $item->award_id }}" data-caption-id="{{ $item->caption_id }}" data-rank="{{ $item->rank }}">
            @if($item->division)
              <span class="division-name">{{ $item->division->name }}</span>
            @endif

            @if($item->award)
              <span class="award-name">{{ $item->award->name }}</span>
            @endif

            @if($item->caption)
              <span class="caption-name caption-{{ $item->caption->slug }}">{{ $item->caption->name }} {{ ordinal($item->rank) }} Place</span>
            @elseif($item->rank)
              <span class="caption-name caption-overall">Overall {{ ordinal($item->rank) }} Place</span>
            @endif
          </li>
        @endforeach
      </ul>
    </div>


    <div class="schedule-builder">
      <div class="schedule-builder-header">
        Awards
      </div>

      <ul class="schedule-builder-list schedule-items divisions">
        @foreach($competition->divisions as $div)
          <li class="division">
            <span class="division-heading">{{ $div->name }}</span>
            <ul class="awards">

              <!-- Begin division overall and caption specific awards -->

              @if($div->overall_award_count > 0)
                <?php $i = 1; ?>
                @while($i <= $div->overall_award_count)
                  <?php
                  $isInSchedule = $schedule->items->where('division_id', $div->id)->where('caption_id', 0)->where('rank', $i)->count();

                  $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('caption_id', 0)->where('rank', $i)->count();
                  ?>
                  @if(!$isInSchedule AND !$isInAnotherSchedule)
                    <li class="schedule-item award" data-division-id="{{ $div->id }}" data-caption-id="0" data-rank="{{ $i }}">
                      <span class="division-name">{{ $div->name }}</span>
                      <span class="caption-name caption-overall">Overall {{ ordinal($i) }} Place</span>
                    </li>
                  @endif
                  <?php $i++; ?>
                @endwhile
              @endif

              @foreach($captions as $caption)
                @if($div->{$caption->slug.'_award_count'} > 0)
                  <?php $i = 1; ?>
                  @while($i <= $div->{$caption->slug.'_award_count'})
                    <?php
                    $isInSchedule = $schedule->items->where('division_id', $div->id)->where('caption_id', $caption->id)->where('rank', $i)->count();

                    $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('caption_id', $caption->id)->where('rank', $i)->count();
                    ?>
                    @if(!$isInSchedule AND !$isInAnotherSchedule)
                      <li class="schedule-item award" data-division-id="{{ $div->id }}" data-caption-id="{{ $caption->id }}" data-rank="{{ $i }}">
                        <span class="division-name">{{ $div->name }}</span>
                        <span class="caption-name caption-{{ $caption->slug }}">{{ $caption->name }} {{ ordinal($i) }} Place</span>
                      </li>
                    @endif
                    <?php $i++; ?>
                  @endwhile
                @endif
              @endforeach

              <!-- End division overall and caption specific awards -->

              @foreach($div->awards as $award)
                <?php
                $isInSchedule = $schedule->items->where('division_id', $div->id)->where('award_id', $award->id)->count();

                $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('award_id', $award->id)->count();
                ?>
                @if(!$isInSchedule AND !$isInAnotherSchedule)
                  <li class="schedule-item award" id="item_{{ $award->pivot_division_id }}_{{ $award->id }}" data-division-id="{{ $div->id }}" data-award-id="{{ $award->id }}">
                    <span class="division-name">{{ $div->name }}</span>
                    <span class="award-name">{{ $award->name }}</span>
                  </li>
                @endif
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    </div>


    <div class="schedule-builder-footer">
      <a href="{{ route('organizer.competition.award-schedule.builder.store', [$competition->id, $schedule->id]) }}" class="save-schedule-btn btn btn-primary">Save Award Ceremony Schedule</a>

      <span class="schedule-builder-status-message"></span>

      <span class="is-dirty-message">
        Your schedule has changed. You must click "Save Schedule" to complete your changes.
      </span>
    </div>
  </div>


@endsection


@section('body-footer')

  <script src="/js/schedule-builder.js"></script>
  <script>
    $( function() {

      ScheduleBuilder.init();

      $('.save-schedule-btn').on('click', function(event) {
        event.preventDefault();
        ScheduleBuilder.save($(this).attr('href'));
      });

      $('ul.schedule').on('sortupdate', function(event, ui) {

      });
    });
  </script>
@endsection
