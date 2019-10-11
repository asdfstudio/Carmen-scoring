@foreach($standings as $standing)

  @if($standing)
    @php
    if($standing->caption_id == NULL)
    {
      $awardSetting = $awardSettings->where('caption_id', 0)->first();
    }
    else
    {
      $awardSetting = $awardSettings->where('caption_id', $standing->caption_id)->first();
    }

    if ($awardSetting) {
      $limit = $awardSetting->award_count;
    } else {
      $limit = 0;
    }

    $standing->choirs = $standing->choirs->take($limit)->reverse();
    @endphp
  @endif

  @if($standing->choirs->count() > 0)
      @include('standing.public_standing_table', ['standing' => $standing, 'showSponsor' => true])
  @endif
@endforeach
