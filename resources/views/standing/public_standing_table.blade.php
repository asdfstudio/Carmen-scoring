@if($standing->choirs)
<table>
  <tr>
    <th colspan="2">
      @if($standing->caption_id == NULL)
        Overall Standings
      @else
        {{ $standing->caption->name }} Standings
      @endif
    </th>
  </tr>

  @php
  $captionId = $standing->caption_id ? $standing->caption_id : 0;
  @endphp

  @foreach($standing->choirs as $choir)
    <tr>
      <td>{{ $choir->full_name }}</td>

      @php
      $rank_name = false;
      $final_rank = $choir->pivot->final_rank;
      //$index = $final_rank - 1;

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
        <td>Sponsored by: {{ $sponsor }}</td>
      @endif


      <td>
          {{ $rank_name }}
      </td>

    </tr>
  @endforeach
</table>
@endif
