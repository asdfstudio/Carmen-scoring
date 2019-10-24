
<table class="table table-striped table-bordered scoreboard toggle-scores rank">
  @foreach($captions as $caption)

    @php
      $captionTotalRank = $rankedScores->total_rank($caption->id); //dd($captionTotalRank);
      $totalWeightedRank = $rankedScores->total_weighted_rank($caption->id); //dd($totalWeightedRank);
      $totalRawRank = $rankedScores->total_raw_rank($caption->id); //dd($totalRawRank);
      $election_key = $division->caption_weighting_id === 1 ? 'caption_'.$caption->id.'_weighted' : 'caption_'.$caption->id;
    @endphp

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    <tr class="align-bottom">
      <th></th>

      @foreach($choirs as $choir)
        <th class="sideways-header">
          {{ link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}
        </th>
      @endforeach
      
      <th>Sum</th>
      
      <th>Rank</th>

      @if(!empty($ratings))
        <th>Rating</th>
      @endif
    </tr>

    @foreach($choirs as $choir)
      <tr>
        <th>
          {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir]) }}
        </th>
        
        @foreach($choirs as $choir_comp)
          <td>
            {{ $rankedScores->pairwise_bit($election_key, $choir->id, $choir_comp->id) }}
          </td>
        @endforeach
        
        <td>
          {{ $rankedScores->pairwise_bit_sum($election_key, $choir->id) }}
        </td>
        
        <td>
          @php $rank = $captionTotalRank->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
          <span class="rank score">{{ $rank }}</span>
        </td>

        @if(!empty($ratings))
          <td></td>
        @endif
      </tr>
    @endforeach
  @endforeach


  @php
    $totalWeightedRank = $rankedScores->total_weighted_rank();
    $totalRawRank = $rankedScores->total_raw_rank();
    $election_key = $division->caption_weighting_id === 1 ? 'overall_weighted' : 'overall';
  @endphp

  <tr class="caption-header caption-place">
    <th colspan="30">
      Place
    </th>
  </tr>

  <tr class="align-bottom">
    <th></th>

    @foreach($choirs as $choir)
      <th class="sideways-header">
        {{ link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}
      </th>
    @endforeach

    <th>Sum</th>

    <th>Rank</th>

    @if(!empty($ratings))
      <th>Rating</th>
    @endif
  </tr>

  @foreach($choirs as $choir)
    <tr>
      <th>
        {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir]) }}
      </th>
      @foreach($choirs as $choir_comp)
        <td>{{ $rankedScores->pairwise_bit($election_key, $choir->id, $choir_comp->id) }}</td>
      @endforeach

      <td>
        {{ $rankedScores->pairwise_bit_sum($election_key, $choir->id) }}
      </td>

      <td>
        @php $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();@endphp
        <span class="rank score">{{ $rank }}</span>
      </td>

      @if(!empty($ratings))
        <td>{{ $ratings->where('choir.id', $choir->id)->pluck('rating.name')->first() }}</td>
      @endif
    </tr>
  @endforeach

</table>
