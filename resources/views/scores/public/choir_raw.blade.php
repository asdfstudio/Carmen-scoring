
@if(!$division->sheet->criteria->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>

    @foreach($division->judges as $judge)
    <th data-judge-id="{{ $judge->id }}">
      {{ link_to_route('results.division.round.judge.show', $judge->full_name, [$division, $round, $judge, $access_code]) }}
    </th>
    @endforeach

  </tr>

  @foreach($captions as $caption)

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)


    <tr data-criterion-id="{{ $criterion->id }}">
    	<td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>

      @foreach($division->judges as $judge)
     	<td data-judge-id="{{ $judge->id }}" data-criterion-id="{{ $criterion->id }}">
      	@php $rawScore = $scoreboard->rawScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->pluck('score');@endphp
        @php $score = $rawScore->first(); @endphp
        {{ $score }}
      </td>
      @endforeach

    </tr>
    @endforeach


    <tr class="caption-raw-score {{ $caption->lighter_background_css }}">
      <th>
        Total Raw {{ $caption->name }} Score
      </th>
      @foreach($division->judges as $judge)
        <th>
          @php $rawTotal = $scoreboard->rawScores->where('criterion_caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->sum('score');@endphp
          {{ $rawTotal }}
        </th>
      @endforeach
    </tr>

    @if($caption->id == 1)
      <tr class="caption-weighted-score {{ $caption->background_css }}">
        <th>
          Total Weighted {{ $caption->name }} Score
        </th>
        @foreach($division->judges as $judge)
          <th>
            @php $weightedTotal = $scoreboard->weightedScores->where('criterion_caption_id', $caption->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->sum('weightedScore');@endphp
            {{ $weightedTotal }}
          </th>
        @endforeach
      </tr>
    @endif

    <tr class="caption-rank {{ $caption->darker_background_css }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      @foreach($division->judges as $judge)
        <th>
          @php $rank = $scoreboard->rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
          {{ $rank }}
        </th>
      @endforeach
    </tr>

  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    @foreach($division->judges as $judge)
    	<th>
      @php $weightedTotal = $scoreboard->rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('weightedScore');@endphp
      {{ $weightedTotal }}
      </th>
    @endforeach

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    @foreach($division->judges as $judge)
    	<th>
        @php $rank = $scoreboard->rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
        {{ $rank }}
      </th>
    @endforeach

  </tr>


</table>
@endif
