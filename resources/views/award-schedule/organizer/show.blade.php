@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>{{ $schedule->name }}</h1>

  <ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.award-schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.award-schedule.builder', 'Build Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{!! form($deleteForm) !!}</li>
	</ul>

@endsection

@section('content')

  <ul class="schedule-list">
    @foreach($schedule->items as $item)

      <?php
      $awardWinner = false;

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id)->first();
      }
      elseif($item->division)
      {
        if($item->caption)
        {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', $item->caption->id)->first();
        }
        else {
          $standing = $standings->where('division_id', $item->division->id)->first();
        }


        if($standing AND $standing->choirs)
        {
          $awardWinner = $standing->choirs()->wherePivot('final_rank', $item->rank)->first();
        }

      }


      ?>

      <li class="schedule-item award">
        @if($item->division)
          <span class="division-name" data-division-id="{{ $item->division->id }}">{{ $item->division->name }}</span>
        @endif

        @if($item->award)
          <span class="award-name">{{ $item->award->name }}</span>
        @endif

        @if($item->caption)
          <span class="caption-name caption-{{ $item->caption->slug }}">{{ $item->caption->name }} {{ ordinal($item->rank) }} Place</span>
        @elseif($item->rank)
          <span class="caption-name caption-overall">Overall {{ ordinal($item->rank) }} Place</span>
        @endif

        @if($awardWinner)
          <span class="award-winner">
            @if($awardWinner->recipient)
              <span class="award-winner-recipient">{{ $awardWinner->recipient }}</span>
            @endif

            @if($awardWinner->choir)
              <span class="award-winner-choir">{{ $awardWinner->choir->full_name }}</span>
            @endif

            @if($awardWinner->full_name)
              <span class="award-winner-choir">{{ $awardWinner->full_name }}</span>
            @endif

          </span>
          @if($awardWinner->sponsor)
            <span class="award-sponsor">{{ $awardWinner->sponsor }}</span>
          @endif
        @endif
      </li>
    @endforeach
  </ul>


@endsection
