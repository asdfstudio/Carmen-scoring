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

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id)->first();
        $sponsor = $awardWinner->sponsor;
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
          $awardWinner = $standing->choirs()->wherePivot('final_rank', $item->rank)->first();
        }
      }

      @endphp

      <li class="schedule-item award">

        <div class="award-heading">

          @if($item->division)
            <span class="division-name" data-division-id="{{ $item->division->id }}">{{ $item->division->name }}</span>
          @endif

          @if($item->round)
            <span class="award-name">{{ $item->round->name }} Ratings</span>
          @endif

          @if($item->award)
            <span class="award-name">{{ $item->award->name }}</span>
          @endif

          @if($item->caption)
            <span class="caption-name {{ $item->caption->text_css }}">{{ $item->caption->name }} {{ $item->named_rank }}</span>
          @elseif($item->rank)
            <span class="caption-name caption-overall">Overall {{ $item->named_rank }}</span>
          @endif

        </div> <!-- end award heading-->


        @if($item->round)
          @php $roundRatings = $ratings->where('round_id', $item->round->id)->first();@endphp

          @if($roundRatings)
            <ul class="list-group">
              @foreach($roundRatings['ratings'] as $rating)
                <li class="list-group-item">{{ $rating['choir']->full_name }}: {{ $rating['rating']['name'] }}</li>
              @endforeach
            </ul>
          @endif

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
        @endif

        @if($sponsor)
          <span class="award-sponsor">Sponsor: {{ $sponsor }}</span>
        @endif


      </li>
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
