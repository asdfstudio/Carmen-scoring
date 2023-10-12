
@php
    $showRating = true;
@endphp
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered scoreboard toggle-scores condorcet">
  @foreach($captions as $caption)

    @php
      if($round->caption_weighting_id === 1){
        $captionTotalRank = $rankedScores->total_weighted_rank($caption->id);
      } else {
        $captionTotalRank = $rankedScores->total_raw_rank($caption->id);
      }
      $election_key = $round->caption_weighting_id === 1 ? 'caption_'.$caption->id.'_weighted' : 'caption_'.$caption->id;
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
          {{-- @if($show_links)
            {{ link_to_route('results.division.round.choir.show', $choir->name, [$division, $round, $choir, $access_code]) }}
          @else --}}
            <span>{{ $choir->name }}</span>
          {{-- @endif --}}
          </div>
        </th>
      @endforeach

      <th>Sum</th>

      <th>Rank</th>

        @if($showRating)
            <th class="">Rating</th>
        @endif
    </tr>

    @foreach($choirs as $choir)
      <tr>
        <th>
          {{-- @if($show_links)
            {{ link_to_route('results.division.round.choir.show', $choir->name, [$division, $round, $choir, $access_code]) }}
          @else --}}
            {{ $choir->name }}
          {{-- @endif --}}
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

          @if($showRating)
              <td class=""></td>
          @endif
      </tr>
    @endforeach
  @endforeach


  @php
    if($round->caption_weighting_id === 1){
      $totalRank = $rankedScores->total_weighted_rank();
    } else {
      $totalRank = $rankedScores->total_raw_rank();
    }
    $election_key = $round->caption_weighting_id === 1 ? 'overall_weighted' : 'overall';
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
          {{-- @if($show_links)
            {{ link_to_route('results.division.round.choir.show', $choir->name, [$division, $round, $choir, $access_code]) }}
          @else --}}
            <span>{{ $choir->name }}</span>
          {{-- @endif --}}
        </div>
      </th>
    @endforeach

    <th>Sum</th>

    <th>Rank</th>

      @if($showRating)
          <th class="">Rating</th>
      @endif
  </tr>

  @foreach($choirs as $choir)
    <tr>
      <th>
        {{-- @if($show_links)
          {{ link_to_route('results.division.round.choir.show', $choir->name, [$division, $round, $choir, $access_code]) }}
        @else --}}
          {{ $choir->name }}
        {{-- @endif --}}
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

        @if($showRating)
            @php
                if(!$choir->pivot->receives_ratings) {
                    $ratingRaw = 'No Rating';
                    $ratingWeight = 'No Rating';
                } else {
                    $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');
                    $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');
                    $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->sum('weightedScore');
                    $rawSubtotal = $rawScores->where('choir_id', $choir->id)->sum('score');

                    $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty;
                    $rawTotal = $rawSubtotal - $penalty - $judgePenalty;

                    $ratingRaw = $rankedScores->getRatingOfChoir($rawTotal, $choir->pivot->division_id);
                    $ratingWeight = $rankedScores->getRatingOfChoir($weightedTotal, $choir->pivot->division_id);
                }
            @endphp
            <td class="raw column-rating total_column">
                {{$ratingRaw}}
            </td>
            <td class="weighted column-rating total_column">
                {{$ratingWeight}}
            </td>
            <td class="average rank condorcet  column-rating total_column">
                @if($round->caption_weighting_id === 1)
                    {{$ratingWeight}}
                @else
                    {{$ratingRaw}}
                @endif
            </td>
        @endif
    </tr>
  @endforeach

</table>
</div>
