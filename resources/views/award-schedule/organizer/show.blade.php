@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>{{ $schedule->name }}</h1>

  <ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.award-schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.award-schedule.builder', 'Build Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.award-schedule.show-announcer', 'Announcer View', [$competition, $schedule], ['class' => 'action']) }}</li>
    <li>{!! form($deleteForm) !!}</li>
	</ul>

@endsection

@section('content')

  <ul class="schedule-list">
    @foreach($schedule->items as $item)

      @php
      $awardWinner = false;
      $tied = false;
      $ratings = null;

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id);
        $tied = $awardWinner->count() > 1 ? true : false;
      }
      elseif($item->division)
      {
        if($item->caption)
        {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', $item->caption->id)->first();
        }
        else {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', NULL)->first();
        }


        if($standing AND $standing->choirs)
        {
          $awardWinner = $standing->choirs->where('pivot.final_rank', $item->rank);
          $tied = $awardWinner->count() > 1 ? true : false;
        }
      }

      if($item->round){
        $ratings = $item->round->getRatings();
      }

      @endphp

      @if(!empty($awardWinner) || !empty($ratings))
        <li class="schedule-item award">
          @if($item->division)
            <span class="division-name" data-division-id="{{ $item->division->id }}">{{ $item->division->name }}</span>
          @endif

          @if($item->round)
            <span class="award-name">{{ $item->round->name }} Ratings</span>
            @foreach($ratings as $rating)
              <span class="award-winner pull-right">
                  <span class="rating">{{ $rating['rating']['name'] }}:</span> <span class="award-winner-choir">{{ $rating['choir']->full_name }}</span>
              </span><br>
            @endforeach
          @endif

          @if($item->award)
            <span class="award-name">{{ $item->award->name }} @if($tied) <span class="tied">tied</span> @endif </span>
          @endif

          @if($item->caption)
            <span class="caption-name {{ $item->caption->text_css }}">{{ $item->caption->name }} {{ $item->named_rank }} @if($tied) <span class="tied">tied</span> @endif </span>
          @elseif($item->named_rank)
            <span class="caption-name caption-overall">Overall {{ $item->named_rank }} @if($tied) <span class="tied">tied</span> @endif </span>
          @endif

          @foreach($awardWinner as $theWinner)
            <span class="award-winner pull-right">
              @if(!empty($theWinner->recipient))
                <span class="award-winner-recipient">{{ $theWinner->recipient }}</span>
              @endif

              @if(!empty($theWinner->choir))
                <span class="award-winner-choir">{{ $theWinner->choir->full_name }}</span>
              @endif

              @if(!empty($theWinner->full_name))
                <span class="award-winner-choir">{{ $theWinner->full_name }}</span>
              @endif

            </span><br>

            @if(!empty($theWinner->sponsor))
              <!--<span class="award-sponsor">{{ $theWinner->sponsor }}</span>-->
            @endif
          @endforeach
        </li>
      @endif
    @endforeach
  </ul>


@endsection
