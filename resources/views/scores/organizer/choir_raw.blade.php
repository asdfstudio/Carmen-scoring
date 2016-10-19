
@if(!$division->sheet->criteria->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>

    @foreach($division->judges as $judge)
    <th data-judge-id="{{ $judge->id }}">
      {{ link_to_route('organizer.round.scores.choir.judge.show',$judge->full_name,[$competition,$division,$round,$choir,$judge]) }}
    </th>
    @endforeach

  </tr>

  @foreach($captions as $caption)

    <tr class="caption-header caption-{{ $caption->slug() }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    @foreach($division->sheet->criteria as $criterion)


    <tr data-criterion-id="{{ $criterion->id }}">
    	<td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>

      @foreach($division->judges as $judge)
     	<td data-judge-id="{{ $judge->id }}" data-criterion-id="{{ $criterion->id }}">
      	<?php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->pluck('score');?>
        <?php $score = $rawScore->first(); ?>
        {{ $score }}
      </td>
      @endforeach

    </tr>
    @endforeach


    <tr class="caption-raw-score caption-{{ $caption->slug() }}">
      <th>
        Total Raw {{ $caption->name }} Score
      </th>
      @foreach($division->judges as $judge)
        <th>
          <?php $rawTotal = $rawScores->where('criterion.caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->sum('score');?>
          {{ $rawTotal }}
        </th>
      @endforeach
    </tr>

    @if($caption->id == 1)
      <tr class="caption-weighted-score caption-{{ $caption->slug() }}">
        <th>
          Total Weighted {{ $caption->name }} Score
        </th>
        @foreach($division->judges as $judge)
          <th>
            <?php $weightedTotal = $weightedScores->where('criterion.caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->sum('weightedScore');?>
            {{ $weightedTotal }}
          </th>
        @endforeach
      </tr>
    @endif

    <tr class="caption-rank caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      @foreach($division->judges as $judge)
        <th>
          <?php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
          {{ $rank }}
        </th>
      @endforeach
    </tr>

  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    @foreach($division->judges as $judge)
    	<th>
      <?php $weightedTotal = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('weightedScore');?>
      {{ $weightedTotal }}
      </th>
    @endforeach

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    @foreach($division->judges as $judge)
    	<th>
        <?php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
        {{ $rank }}
      </th>
    @endforeach

  </tr>


</table>
@endif
