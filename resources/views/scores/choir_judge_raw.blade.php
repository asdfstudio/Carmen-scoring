@if(!$division->sheet->criteria->isEmpty())

<?php
if($division->captionWeighting->slug == '60-40') :
  $toggle_scores = 'toggle-scores';
else :
  $toggle_scores = false;
endif;
?>

<table class="table table-bordered scoreboard last-col-right">
  <!--<tr>
  	<th>Criteria</th>
    <th>Score</th>
  </tr>

  <tr>
  	<th>Total Score</th>
    <th colspan="2">
      <?php $score = $rawScores->sum('score'); ?>
      {{ $score }}
    </th>
  </tr>-->

  @foreach($captions as $caption)
    <tr class="caption-header caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }}
      </th>
      <th class="score raw">
        Raw Score
      </th>

      @if($division->captionWeighting->slug == '60-40')
        <th class="score weighted">
          Weighted Score
        </th>
      @endif
    </tr>

    @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr>
    	<td>{{ $criterion->name }}</td>
      <td>
      	<?php
        $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('score');
        $score = $rawScore->first();
        ?>
        <span class="score raw">{{ $score }}</span>
      </td>

      @if($division->captionWeighting->slug == '60-40')
        <td>
          <?php
          $weightedScore = $scoreboard->weightedScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('weightedScore');
          $score = $weightedScore->first();
          ?>
          <span class="score weighted">{{ $score }}</span>
        </td>
      @endif

    </tr>
    @endforeach


    <tr class="caption-raw-score caption-{{ $caption->slug() }}">
      <th>
        Total {{ $caption->name }} Score
      </th>
      <th>
        <?php $rawTotal = $rawScores->where('criterion.caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('score');?>
        {{ $rawTotal }}
      </th>

      @if($division->captionWeighting->slug == '60-40')
        <th>
          <?php $weightedTotal = $weightedScores->where('criterion.caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
          {{ $weightedTotal }}
        </th>
      @endif
    </tr>

    <tr class="caption-rank caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      <th colspan="2">
        <?php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
        {{ $rank }}
      </th>
    </tr>


  @endforeach


  <tr class="total-score">
  	<th>Total Score</th>

    <th>
      <?php $rawTotal = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('score');?>
      {{ $rawTotal }}
    </th>

    @if($division->captionWeighting->slug == '60-40')
      <th>
        <?php $weightedTotal = $weightedScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
        {{ $weightedTotal }}
      </th>
    @endif

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    <th colspan="2">
      <?php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
      {{ $rank }}
    </th>

  </tr>



</table>
@endif
