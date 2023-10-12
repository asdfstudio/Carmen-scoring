@php
  // Hide the "Total" column for Consensus Ordinal Rank (scoring method 5)
  $total_col_class = 'total_column weighted raw average rank';
  if($round->scoring_method_id == 5){
    $total_col_class = 'total_column weighted raw';
  }
  $showRating = true;
@endphp
@if($rawScores->count() === 0)

<p class="alert alert-warning">Scores have not been entered. Please try again after judges have entered scores.</p>

@else
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered scoreboard toggle-scores weighted raw average rank">
  @foreach($captions as $caption)

    @php
      if($round->caption_weighting_id === 1){
        $captionTotalRank = $rankedScores ? $rankedScores->total_weighted_rank($caption->id) : 0;
      } else {
        $captionTotalRank = $rankedScores ? $rankedScores->total_raw_rank($caption->id) : 0;
      }
      $totalWeightedRank = $rankedScores ? $rankedScores->total_weighted_rank($caption->id): 0;
      $totalRawRank =$rankedScores ? $rankedScores->total_raw_rank($caption->id) : 0;
    @endphp

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="3">
        {{ $caption->name }}
      </th>
      <th colspan="30"></th>
    </tr>

    <tr>
      <th></th>

      @foreach($judges as $judge)
        <th>
          {{ $judge->full_name }}
        </th>
      @endforeach

      <th class="{{ $total_col_class }} total-col">Total</th>

      <th>Place</th>
        @if($showRating)
        <th class="">Rating</th>
        @endif
    </tr>

    @foreach($choirs as $choir)
      <tr>
        <th>
        {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$competition, $choir->pivot->division_id, $round, $choir]) }}
        </th>
        @foreach($judges as $judge)

          @if($judge->captions->where('id',$caption->id)->count() == 0)
            <td>-</td>
          @endif

          @if($judge->captions->where('id',$caption->id)->count() > 0)
            <td>
              @php
                $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();

                $tied = $round->scoring_method_id !== 1 && !empty($rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
              @endphp
              <span class="rank score {{ $tied }}">{{ $rank }} </span>

              @php $weighted = $weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');@endphp
              <span class="weighted score {{ $tied }}">{{ $weighted }}</span>

              @php $raw = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');@endphp
              <span class="raw average score {{ $tied }}">{{ $raw }}</span>

            </td>
          @endif

        @endforeach

        <td class="{{ $total_col_class }}">
          @php $rank = $rankedScores->total($choir->id, $caption->id);@endphp
          <span class="rank score">{{ $rank }}</span>

          @php $weighted = $weightedScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');@endphp
          <span class="weighted score">{{ $weighted }}</span>

          @php $raw = $rawScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('score');@endphp
          <span class="raw score">{{ $raw }}</span>

          @php $average = round($rawScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('score') / count($judges), 1);@endphp
          <span class="average score">{{ $average }}</span>
        </td>

        <td>
          @php
            $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
            $tied = !empty($captionTotalRank->where('choir_id' , $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          @endphp
          <span class="rank score {{ $tied }}">{{ $rank }}</span>

          @php $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
          <span class="weighted score {{ $tied }}">{{ $rank }}</span>

          @php $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
          <span class="raw average score {{ $tied }}">{{ $rank }}</span>
        </td>
          @if($showRating)
            <td class=""></td>
          @endif
      </tr>
    @endforeach
  @endforeach


  <tr class="caption-header caption-place">
    <th colspan="3">
      Place
    </th>
    <th colspan="30"></th>
  </tr>

  <tr>
    <th></th>

    @foreach($judges as $judge)
      <th>
        {{ $judge->full_name }}
      </th>
    @endforeach

    @if($round->scoring_method_id !== 5)
      <th class="total-col">Total</th>
    @endif

    <th>Place</th>
      @if($showRating)
      <th class=" ">Rating</th>
      @endif
  </tr>

  @php
    if($round->caption_weighting_id === 1){
      $totalRank = $rankedScores->total_weighted_rank();
    } else {
      $totalRank = $rankedScores->total_raw_rank();
    }
    $totalWeightedRank = $rankedScores->total_weighted_rank();
    $totalRawRank = $rankedScores->total_raw_rank();
  @endphp

  @foreach($choirs as $choir)
    <tr>
      <th>
        {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$competition, $choir->pivot->division_id, $round, $choir]) }}
      </th>
      @foreach($judges as $judge)
        <td>
          @php
            $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();
            $tied = !empty($rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          @endphp
          <span class="rank score {{ $tied }}">{{ $rank }}</span>

          @php $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');@endphp
          <span class="weighted subtotal score">{{ $weightedSubtotal }}</span>

          @php $rawSubtotal = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');@endphp
          <span class="raw average score">{{ $rawSubtotal }}</span>

          @php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');@endphp
          <span class="penalty raw average weighted score">{{ $penalty }}</span>

          @php $weightedTotal = $weightedSubtotal - $penalty; @endphp
          <span class="weighted total score">{{ $weightedTotal }}</span>

          @php $rawTotal = $rawSubtotal - $penalty; @endphp
          <span class="raw total average score">{{ $rawTotal }}</span>

        </td>
      @endforeach

      <td class="{{ $total_col_class }}">
        @php $rank = $rankedScores->total($choir->id);@endphp
        <span class="rank score">{{ $rank }}</span>

        @php $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->sum('weightedScore');@endphp
        <span class="weighted subtotal score">{{ $weightedSubtotal }}</span>

        @php $rawSubtotal = $rawScores->where('choir_id', $choir->id)->sum('score');@endphp
        <span class="raw score">{{ $rawSubtotal }}</span>

        @php $averageTotal = round($rawScores->where('choir_id', $choir->id)->sum('score')/count($judges), 1);@endphp
        <span class="average score">{{ $averageTotal }}</span>

        @php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');@endphp
        <span class="penalty raw weighted overall score">{{ $penalty }}</span>

        @php $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');@endphp
        <span class="penalty raw weighted judge score">{{ $judgePenalty }}</span>

        @php $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty; @endphp
        <span class="weighted total score">{{ $weightedTotal }}</span>

        @php $rawTotal = $rawSubtotal - $penalty - $judgePenalty; @endphp
        <span class="raw total score">{{ $rawTotal }}</span>

      </td>

      <td>
        @php
          $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $totalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
          $tied = !empty($totalRank->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
        @endphp
        <span class="rank score {{ $tied }}">{{ $rank }}</span>

        @php $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
        <span class="weighted score {{ $tied }}">{{ $rank }}</span>

        @php
          $rank = !$choir->pivot->receives_rankings ? 'No Rank' : $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first();
        @endphp
        <span class="raw average score {{ $tied }}">{{ $rank }}</span>


      </td>
        @if($showRating)
            @php
                if(!$choir->pivot->receives_ratings) {
                    $ratingRaw = 'No Rating';
                    $ratingWeight = 'No Rating';
                } else {
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
            <td class="average rank column-rating total_column">
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
@endif
