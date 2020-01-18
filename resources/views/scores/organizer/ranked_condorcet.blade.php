@if($rawScores->count() === 0)

<p class="alert alert-warning">Scores have not been entered. Please try again after judges have entered scores.</p>

@else
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered scoreboard toggle-scores condorcet">
  @foreach($captions as $caption)

    @php
      if($division->caption_weighting_id === 1){
        $captionTotalRank = $rankedScores->total_weighted_rank($caption->id);
      } else {
        $captionTotalRank = $rankedScores->total_raw_rank($caption->id);
      }
      $election_key = $division->caption_weighting_id === 1 ? 'caption_'.$caption->id.'_weighted' : 'caption_'.$caption->id;
    @endphp

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="3">
        {{ $caption->name }}
      </th>
      <th colspan="30"></th>
    </tr>

    <tr class="align-bottom">
      <th></th>

      @foreach($choirs as $choir)
        <th>
          <div class="sideways-header">
            {{ link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}
          </div>
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
          @php
            $rank = $captionTotalRank->where('choir_id', $choir->id)->pluck('rank')->first();
            $tied = !empty($captionTotalRank->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          @endphp
          <span class="condorcet score {{ $tied }}">{{ $rank }}</span>
        </td>

        @if(!empty($ratings))
          <td></td>
        @endif
      </tr>
    @endforeach
  @endforeach


  @php
    if($division->caption_weighting_id === 1){
      $totalRank = $rankedScores->total_weighted_rank();
    } else {
      $totalRank = $rankedScores->total_raw_rank();
    }
    $election_key = $division->caption_weighting_id === 1 ? 'overall_weighted' : 'overall';
  @endphp

  <tr class="caption-header caption-place">
    <th colspan="3">
      Place
    </th>
    <th colspan="30"></th>
  </tr>

  <tr class="align-bottom">
    <th></th>

    @foreach($choirs as $choir)
      <th>
        <div class="sideways-header">
          {{ link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}
        </div>
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
        @php
          $rank = $totalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
          $tied = !empty($totalRank->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
        @endphp
        <span class="condorcet score {{ $tied }}">{{ $rank }}</span>
      </td>

      @if(!empty($ratings))
        <td>{{ $ratings->where('choir.id', $choir->id)->pluck('rating.name')->first() }}</td>
      @endif
    </tr>
  @endforeach

</table>
</div>
@endif