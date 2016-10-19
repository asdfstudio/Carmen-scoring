@if(!$division->sheet->criteria->isEmpty())

<!--<ul class="list-group horizontal full-width">

  @foreach($captions as $caption)
    <li class="list-group-item">

      <span class="attr_label">{{ $caption->name }} Score</span>

      <?php $score = $rawScores->where('criterion.caption_id', $caption->id)->sum('score'); ?>
      <span class="attr_value">{{ $score }}</span>
    </li>
  @endforeach

  <li class="list-group-item">
    <span class="attr_label">Total Score</span>

    <?php $score = $rawScores->sum('score'); ?>
    <span class="attr_value">{{ $score }}</span>
  </li>
</ul>-->

<table class="table table-bordered last-col-right">
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
      <th colspan="2">
        {{ $caption->name }}
      </th>
    </tr>

    @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr>
    	<td>{{ $criterion->name }}</td>
      <td>
      	<?php
        $rawScore = $rawScores->where('criterion_id', $criterion->id)->pluck('score');
        $score = $rawScore->first();
        ?>
        {{ $score }}
      </td>
    </tr>
    @endforeach


    <tr class="caption-raw-score caption-{{ $caption->slug() }}">
      <th>
        Total Raw {{ $caption->name }} Score
      </th>
      <th>
        <?php $rawTotal = $rawScores->where('criterion.caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('score');?>
        {{ $rawTotal }}
      </th>
    </tr>

    @if($caption->id == 1)
      <tr class="caption-weighted-score caption-{{ $caption->slug() }}">
        <th>
          Total Weighted {{ $caption->name }} Score
        </th>
        <th>
          <?php $weightedTotal = $weightedScores->where('criterion.caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
          {{ $weightedTotal }}
        </th>
      </tr>
    @endif

    <tr class="caption-rank caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      <th>
        <?php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
        {{ $rank }}
      </th>
    </tr>


  @endforeach


  <tr class="total-score">
  	<th>Total Score</th>

    <th>
      <?php $weightedTotal = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('weightedScore');?>
      {{ $weightedTotal }}
    </th>

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    <th>
      <?php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
      {{ $rank }}
    </th>

  </tr>



</table>
@endif
