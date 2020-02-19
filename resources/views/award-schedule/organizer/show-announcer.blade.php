@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>{{ $schedule->name }}</h1>

@endsection

@section('content')

  <ul class="schedule-list announcer-view">
    @foreach($schedule->items as $item)

      @php
      $awardWinner = false;
      $sponsor = false;
      $tied = false;
      $ratings = null;

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id);
        $tied = $awardWinner->count() > 1 ? true : false;
        if(!empty($awardWinner->first()->sponsor)){
          $sponsor = $awardWinner->first()->sponsor;
        }
      }
      elseif($item->division)
      {
        if($item->caption)
        {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', $item->caption->id)->first();

          $sponsor = $item->division->awardSettings->where('caption_id', $item->caption->id)->first()->awardSponsor($item->rank);
        }
        else {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', null)->first();
          $sponsor = $item->division->awardSettings->where('caption_id', 0)->first()->awardSponsor($item->rank);
          //dd($item->division->awardSettings->where('caption_id', 0)->first()->awardSponsor($item->rank));
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

          <div class="award-heading">

            @if($item->division)
              <span class="division-name" data-division-id="{{ $item->division->id }}">{{ $item->division->name }}</span>
            @endif

            @if($item->round)
              <span class="award-name">{{ $item->round->name }} Ratings</span>
            @endif

            @if($item->award)
              <span class="award-name">{{ $item->award->name }} @if($tied) <span class="tied">tied</span> @endif </span>
            @endif

            @if($item->caption)
              <span class="caption-name {{ $item->caption->text_css }}">{{ $item->caption->name }} {{ $item->named_rank }} @if($tied) <span class="tied">tied</span> @endif </span>
            @elseif($item->rank)
              <span class="caption-name caption-overall">Overall {{ $item->named_rank }} @if($tied) <span class="tied">tied</span> @endif </span>
            @endif

          </div> <!-- end award heading-->


          @if($item->round)
              <ul class="list-group">
                @foreach($ratings as $rating)
                  <li class="list-group-item"><span class="rating">{{ $rating['rating']['name'] }}:</span> <span class="award-winner-choir">{{ $rating['choir']->full_name }}</span></li>
                @endforeach
              </ul>
          @endif

          @foreach($awardWinner as $theWinner)
            <span class="award-winner">
              @if(!empty($theWinner->recipient))
                <span class="award-winner-recipient">{{ $theWinner->recipient }}</span>
              @endif

              @if(!empty($theWinner->choir))
                <span class="award-winner-choir">{{ $theWinner->choir->full_name }}</span>
              @endif

              @if(!empty($theWinner->full_name))
                <span class="award-winner-choir">{{ $theWinner->full_name }}</span>
              @endif

            </span>
          @endforeach

          @if($sponsor)
            <span class="award-sponsor">Sponsor: {{ $sponsor }}</span>
          @endif


        </li>
      @endif
    @endforeach
  </ul>


@endsection


@section('body-footer')

  <script>
    $( function() {
      $('li.schedule-item').on('click', function(event) {
        event.preventDefault();
        $(this).toggleClass('done');
      });
    });
  </script>
@endsection
