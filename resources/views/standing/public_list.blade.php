@if(!$standing->choirs)
  <p>
    No standings to display
  </p>
@endif


@php
$captionId = $standing->caption_id ? $standing->caption_id : 0;
@endphp

@if($standing->choirs)
<ul class="list-group">
  @foreach($standing->choirs as $choir)
    @php $rating = $division->getRatings()->where('choir.id', $choir->id)->pluck('rating.name')->first(); @endphp
    <li class="list-group-item standing">
      <span class="choir">{{ $choir->full_name }}</span> @if($rating)<span class="rating">Rating: {{ $rating }}</span>@endif

      @php
      $rank_name = false;
      $final_rank = $choir->pivot->final_rank;
      //$index = $final_rank - 1;
      $tied = $standing->choirs->where('pivot.final_rank', $choir->pivot->final_rank)->count() > 1 ? true : false;

      //$sponsor = array_key_exists($index, $sponsors) ? $sponsors[$index] : false;

      $awardSetting = $division->awardSettings->where('caption_id', $captionId)->first();

      if ($awardSetting) {
        $sponsor = $awardSetting->awardSponsor($final_rank);
      } else {
        $sponsor = false;
      }


      if($division->competition->use_runner_up_names)
      {
        if($final_rank == 1)
        {
          $rank_name = 'Grand Champion';
        }
        else
        {
          $runner_up_number = $final_rank - 1;
          $rank_name = ordinal($runner_up_number) . ' Runner Up';
        }
      }
      else {
        $rank_name = ordinal($final_rank);
      }
      @endphp

      @if($sponsor AND $showSponsor)
        <span>Sponsored by: {{ $sponsor }}</span>
      @endif

      <div class="details">

        <span class="final_rank ceremony rank-{{ $choir->pivot->final_rank }}">
          {{ $rank_name }}
        </span>
        @if($tied)
          <span class="tied">tied</span>
        @endif

      </div>

    </li>
  @endforeach
</ul>
@endif
