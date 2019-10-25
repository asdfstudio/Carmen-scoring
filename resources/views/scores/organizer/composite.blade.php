@php
  // The composite table gets a different class for Condorcet methods (3 & 4) to hide it when showing Condorcet ranks.
  $composite_table_class = 'weighted raw';
  if($division->scoring_method_id !== 3 && $division->scoring_method_id !== 4){
    $composite_table_class = $composite_table_class . ' rank';
  }

  // Hide the "Total" column for Consensus Ordinal Rank (scoring method 5)
  $total_col_class = 'total_column weighted raw rank';
  if($division->scoring_method_id == 5){
    $total_col_class = 'total_column weighted raw';
  }
@endphp
<table class="table table-striped table-bordered scoreboard toggle-scores {{ $composite_table_class }}">
  @foreach($captions as $caption)

    @php
      $captionTotalRank = $rankedScores->total_rank($caption->id);
      $totalWeightedRank = $rankedScores->total_weighted_rank($caption->id);
      $totalRawRank = $rankedScores->total_raw_rank($caption->id);
    @endphp

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    <tr>
      <th></th>

      @foreach($judges as $judge)
        <th>
          {{ $judge->full_name }}
        </th>
      @endforeach
      
      <th class="{{ $total_col_class }}">Total</th>
      
      <th>Place</th>

      @if(!empty($ratings))
        <th>Rating</th>
      @endif
    </tr>

    @foreach($choirs as $choir)
      <tr>
        <th>
          {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir]) }}
        </th>
        @foreach($judges as $judge)

          @if($judge->captions->where('id',$caption->id)->count() == 0)
            <td>-</td>
          @endif

          @if($judge->captions->where('id',$caption->id)->count() > 0)
            <td>
              @php
                $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();
                $tied = !empty($rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
              @endphp
              <span class="rank score {{ $tied }}">{{ $rank }}</span>

              @php $weighted = $weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');@endphp
              <span class="weighted score">{{ $weighted }}</span>

              @php $raw = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');@endphp
              <span class="raw score">{{ $raw }}</span>

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
        </td>
        
        <td>
          @php
            $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
            $tied = !empty($captionTotalRank->where('choir_id' , $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          @endphp
          <span class="rank score {{ $tied }}">{{ $rank }}</span>

          @php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
          <span class="weighted score">{{ $rank }}</span>

          @php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
          <span class="raw score">{{ $rank }}</span>
        </td>

        @if(!empty($ratings))
          <td></td>
        @endif
      </tr>
    @endforeach
  @endforeach


  <tr class="caption-header caption-place">
    <th colspan="30">
      Place
    </th>
  </tr>

  <tr>
    <th></th>

    @foreach($judges as $judge)
      <th>
        {{ $judge->full_name }}
      </th>
    @endforeach
    
    @if($division->scoring_method_id !== 5)
    <th>Total</th>
    @endif
    
    <th>Place</th>

    @if(!empty($ratings))
      <th>Rating</th>
    @endif
  </tr>

  @php
    $totalWeightedRank = $rankedScores->total_weighted_rank();
    $totalRawRank = $rankedScores->total_raw_rank();
  @endphp

  @foreach($choirs as $choir)
    <tr>
      <th>
        {{ link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir]) }}
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

          @php $raw = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');@endphp
          <span class="raw score">{{ $raw }}</span>

          @php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');@endphp
          <span class="penalty weighted score">{{ $penalty }}</span>

          @php $weightedTotal = $weightedSubtotal - $penalty; @endphp
          <span class="weighted total score">{{ $weightedTotal }}</span>
        </td>
      @endforeach
      
      <td class="{{ $total_col_class }}">
        @php $rank = $rankedScores->total($choir->id);@endphp
        <span class="rank score">{{ $rank }}</span>

        @php $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->sum('weightedScore');@endphp
        @php //$weighted = $weightedScores->total($choir->id);@endphp
        <span class="weighted subtotal score">{{ $weightedSubtotal }}</span>

        @php $raw = $rawScores->where('choir_id', $choir->id)->sum('score');@endphp
        <span class="raw score">{{ $raw }}</span>

        @php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');@endphp
        <span class="penalty weighted overall score">{{ $penalty }}</span>

        @php $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');@endphp
        <span class="penalty weighted judge score">{{ $judgePenalty }}</span>

        @php $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty; @endphp
        <span class="weighted total score">{{ $weightedTotal }}</span>

      </td>
      
      <td>
        @php
          $rank = $rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('rank')->first();
          $tied = !empty($rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('tied')->first()) ? 'tied' : '';
        @endphp
        <span class="rank score {{ $tied }}">{{ $rank }}</span>

        @php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
        <span class="weighted score">{{ $rank }}</span>

        @php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); @endphp
        <span class="raw score">{{ $rank }}</span>


      </td>

      @if(!empty($ratings))
        <td>{{ $ratings->where('choir.id', $choir->id)->pluck('rating.name')->first() }}</td>
      @endif

    </tr>
  @endforeach

</table>
