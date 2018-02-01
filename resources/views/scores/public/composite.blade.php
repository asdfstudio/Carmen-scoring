
<table class="table table-striped table-bordered scoreboard toggle-scores">
  @foreach($captions as $caption)

    <?php $captionTotalRank = $scoreboard->rankedScores->total_rank($caption->id);?>
    <?php $totalWeightedRank = $scoreboard->rankedScores->total_weighted_rank($caption->id); ?>
    <?php $totalRawRank = $scoreboard->rankedScores->total_raw_rank($caption->id); ?>

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    <tr>
      <th></th>

      @foreach($judges as $judge)
        <th>
          @if($show_links)
            {{ link_to_route('results.division.round.judge.show', $judge->full_name, [$division, $round, $judge, $access_code]) }}
          @else
            {{ $judge->full_name }}
          @endif
        </th>
      @endforeach

      <th>Total</th>
      <th>Place</th>

      @if(!empty($ratings))
        <th>Rating</th>
      @endif
    </tr>

    @foreach($choirs as $choir)
      <tr>
        <th>
          @if($show_links)
            {{ link_to_route('results.division.round.choir.show', $choir->full_name, [$division, $round, $choir, $access_code]) }}
          @else
            {{ $choir->full_name }}
          @endif
        </th>
        @foreach($judges as $judge)

          @if($judge->captions->where('id',$caption->id)->count() == 0)
            <td>-</td>
          @endif

          @if($judge->captions->where('id',$caption->id)->count() > 0)
            <td>
              <?php $rank = $scoreboard->rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
              <span class="rank score">{{ $rank }}</span>

              <?php $weighted = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
              <span class="weighted score">{{ $weighted }}</span>

              <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
              <span class="raw score">{{ $raw }}</span>

            </td>
          @endif

        @endforeach

        <td>
          <?php $rank = $scoreboard->rankedScores->total($choir->id, $caption->id);?>
          <span class="rank score">{{ $rank }}</span>

          <?php $weighted = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
          <span class="weighted score">{{ $weighted }}</span>

          <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
          <span class="raw score">{{ $raw }}</span>
        </td>
        <td>
          <?php $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();?>
          <span class="rank score">{{ $rank }}</span>

          <?php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
          <span class="weighted score">{{ $rank }}</span>

          <?php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
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
        @if($show_links)
          {{ link_to_route('results.division.round.judge.show', $judge->full_name, [$division, $round, $judge, $access_code]) }}
        @else
          {{ $judge->full_name }}
        @endif
      </th>
    @endforeach

    <th>Total</th>
    <th>Place</th>

    @if(!empty($ratings))
      <th>Rating</th>
    @endif
  </tr>

  <?php $totalWeightedRank = $scoreboard->rankedScores->total_weighted_rank(); ?>
  <?php $totalRawRank = $scoreboard->rankedScores->total_raw_rank(); ?>

  @foreach($choirs as $choir)
    <tr>
      <th>
        @if($show_links)
          {{ link_to_route('results.division.round.choir.show', $choir->full_name, [$division, $round, $choir, $access_code]) }}
        @else
          {{ $choir->full_name }}
        @endif
      </th>
      @foreach($judges as $judge)
        <td>
          <?php $rank = $scoreboard->rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
          <span class="rank score">{{ $rank }}</span>

          <?php $weightedSubtotal = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
          <span class="weighted subtotal score">{{ $weightedSubtotal }}</span>

          <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');?>
          <span class="raw score">{{ $raw }}</span>

          <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
          <span class="penalty weighted score">{{ $penalty }}</span>

          <?php $weightedTotal = $weightedSubtotal - $penalty; ?>
          <span class="weighted total score">{{ $weightedTotal }}</span>
        </td>

      @endforeach

      <td>
        <?php $rank = $scoreboard->rankedScores->total($choir->id);?>
        <span class="rank score">{{ $rank }}</span>

        <?php $weightedSubtotal = $scoreboard->weightedScores->where('choir_id', $choir->id)->sum('weightedScore');?>
        <?php //$weighted = $scoreboard->weightedScores->total($choir->id);?>
        <span class="weighted subtotal score">{{ $weightedSubtotal }}</span>

        <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->sum('score');?>
        <span class="raw score">{{ $raw }}</span>

        <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');?>
        <span class="penalty weighted overall score">{{ $penalty }}</span>

        <?php $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
        <span class="penalty weighted judge score">{{ $judgePenalty }}</span>

        <?php $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty; ?>
        <span class="weighted total score">{{ $weightedTotal }}</span>

      </td>
      <td>
        <?php $rank = $scoreboard->rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('rank')->first();?>
        <span class="rank score">{{ $rank }}</span>

        <?php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
        <span class="weighted score">{{ $rank }}</span>

        <?php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
        <span class="raw score">{{ $rank }}</span>


      </td>

      @if(!empty($ratings))
        <td>{{ $ratings->where('choir.id', $choir->id)->pluck('rating.name')->first() }}</td>
      @endif
    </tr>
  @endforeach

</table>
