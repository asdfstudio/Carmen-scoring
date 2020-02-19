@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>{{ $schedule->name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.award-schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.award-schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}</li>
	</ul>

@endsection

@section('content')

  <p>Build your schedule by dragging awards to the schedule.</p>

  <div class="schedule-builder-container">
    <div class="schedule-builder">
      <div class="schedule-builder-header">
        Award Ceremony Schedule
      </div>
      <ul class="schedule-builder-list schedule">
        @foreach($schedule->items as $item)
          <li class="schedule-item award" data-division-id="{{ $item->division_id }}" data-round-id="{{ $item->round_id }}" data-award-id="{{ $item->award_id }}" data-caption-id="{{ $item->caption_id }}" data-rank="{{ $item->rank }}">
            <!-- {{ print_r($item) }} -->
            @if($item->division)
              <span class="division-name">{{ $item->division->name }}</span>
            @endif

            @if($item->round)
              <span class="award-name">{{ $item->round->name }} Ratings</span>
            @endif

            @if($item->award)
              <span class="award-name">{{ $item->award->name }}</span>
            @endif

            @if($item->caption)
              <span class="caption-name {{ $item->caption->text_css }}">{{ $item->caption->name }} {{ ordinal($item->rank) }} Place</span>
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

              @foreach ($div->awardSettings as $awardSetting)
                @if($awardSetting->award_count > 0)
                  @php
                  $i = 1;

                  if ($awardSetting->caption) {
                    $captionName = $awardSetting->caption->name;
                    $captionSlug = $awardSetting->caption->slug;
                    $captionCss = $awardSetting->caption->text_css;
                  } else {
                    $captionName = 'Overall';
                    $captionSlug = 'overall';
                    $captionCss = false;
                  }
                  @endphp
                  @while($i <= $awardSetting->award_count)
                    @php
                    $isInSchedule = $schedule->items->where('division_id', $div->id)->where('caption_id', $awardSetting->caption_id)->where('rank', $i)->count();

                    $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('caption_id', $awardSetting->caption_id)->where('rank', $i)->count();
                    @endphp
                    @if(!$isInSchedule AND !$isInAnotherSchedule)
                      <li class="schedule-item award" data-division-id="{{ $div->id }}" data-caption-id="{{ $awardSetting->caption_id }}" data-rank="{{ $i }}">
                        <span class="division-name">{{ $div->name }}</span>
                        <span class="caption-name {{ $captionCss }}">{{ $captionName}} {{ ordinal($i) }} Place</span>
                      </li>
                    @endif
                    @php $i++; @endphp
                  @endwhile
                @endif
              @endforeach


              <!-- End division overall and caption specific awards -->

              <!-- Begin Round Ratings -->
              @foreach ($div->rounds as $round)
                @php
                $isInSchedule = $schedule->items->where('division_id', $div->id)->where('round_id', $round->id)->count();

                $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('round_id', $round->id)->count();
                @endphp
                @if(!$isInSchedule AND !$isInAnotherSchedule)
                  <li class="schedule-item award" data-division-id="{{ $div->id }}" data-round-id="{{ $round->id }}" data-rating="1">
                    <span class="division-name">{{ $div->name }}</span>
                    <span class="award-name rating">{{ $round->name }} Ratings</span>
                  </li>
                @endif
              @endforeach
              <!-- End Round Ratings -->


              @foreach($div->awards as $award)
                @php
                $isInSchedule = $schedule->items->where('division_id', $div->id)->where('award_id', $award->id)->count();

                $isInAnotherSchedule = $excludedScheduleItems->where('division_id', $div->id)->where('award_id', $award->id)->count();
                @endphp
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
