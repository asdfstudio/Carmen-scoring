@if(!$division->sheet->criteria->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>&nbsp;</th>

    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">
      {{ link_to_route('results.division.round.choir.show', $choir->full_name, [$division, $round, $choir, $access_code]) }}</th>
    @endforeach
  </tr>

  @foreach($judge->captions as $caption)

    <tr class="caption-header caption-{{ $caption->slug() }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->name }}</td>

      @foreach($division->choirs as $choir)
      <td data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}">
          <?php $rawScore = $scoreboard->rawScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->where('choir_id',$choir->id)->pluck('score');?>
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
      @foreach($division->choirs as $choir)
        <th>
          <?php $rawTotal = $scoreboard->rawScores->where('criterion.caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('score');?>
          {{ $rawTotal }}
        </th>
      @endforeach
    </tr>

    @if($caption->id == 1)
      <tr class="caption-weighted-score caption-{{ $caption->slug() }}">
        <th>
          Total Weighted {{ $caption->name }} Score
        </th>
        @foreach($division->choirs as $choir)
          <th>
            <?php $weightedTotal = $scoreboard->weightedScores->where('criterion.caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('weightedScore');?>
            {{ $weightedTotal }}
          </th>
        @endforeach
      </tr>
    @endif

    <tr class="caption-rank caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      @foreach($division->choirs as $choir)
        <th>
          <?php $rank = $scoreboard->rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
          {{ $rank }}
        </th>
      @endforeach
    </tr>
  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    @foreach($division->choirs as $choir)
    	<th>
      <?php $weightedTotal = $scoreboard->rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('weightedScore');?>
      {{ $weightedTotal }}
      </th>
    @endforeach

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    @foreach($division->choirs as $choir)
    	<th>
        <?php $rank = $scoreboard->rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
        {{ $rank }}
      </th>
    @endforeach

  </tr>
</table>
@endif
