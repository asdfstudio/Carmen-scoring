@if(!$division->sheet->criteria->isEmpty())

@php
if($division->captionWeighting->slug == '60-40') :
  $toggle_scores = 'toggle-scores';
else :
  $toggle_scores = false;
endif;
@endphp

<div class="table-wrapper-responsive">
<table class="table table-bordered scoreboard last-col-right">
  <!--<tr>
  	<th>Criteria</th>
    <th>Score</th>
  </tr>

  <tr>
  	<th>Total Score</th>
    <th colspan="2">
      @php $score = $rawScores->sum('score'); @endphp
      {{ $score }}
    </th>
  </tr>-->

  @foreach($captions as $caption)
    <tr class="caption-header {{ $caption->background_css }}">
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
      	@php
        $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('score');
        $score = $rawScore->first();
        @endphp
        <span class="score raw">{{ $score }}</span>
      </td>

      @if($division->captionWeighting->slug == '60-40')
        <td>
          @php
          $weightedScore = $scoreboard->weightedScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('weightedScore');
          $score = $weightedScore->first();
          @endphp
          <span class="score weighted">{{ $score }}</span>
        </td>
      @endif

    </tr>
    @endforeach


    <tr class="caption-raw-score {{ $caption->lighter_background_css }}">
      <th>
        Total {{ $caption->name }} Score
      </th>
      <th>
        @php $rawTotal = $rawScores->where('criterion_caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('score');@endphp
        {{ $rawTotal }}
      </th>

      @if($division->captionWeighting->slug == '60-40')
        <th>
          @php $weightedTotal = $weightedScores->where('criterion_caption_id', $caption->id)->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');@endphp
          {{ $weightedTotal }}
        </th>
      @endif
    </tr>

    <tr class="caption-rank {{ $caption->darker_background_css }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      <th colspan="2">
        @php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
        {{ $rank }}
      </th>
    </tr>


  @endforeach


  <tr class="total-score">
  	<th>Total Score</th>

    <th>
      @php $rawTotal = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('score');@endphp
      {{ $rawTotal }}
    </th>

    @if($division->captionWeighting->slug == '60-40')
      <th>
        @php $weightedTotal = $weightedScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');@endphp
        {{ $weightedTotal }}
      </th>
    @endif

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    <th colspan="2">
      @php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
      {{ $rank }}
    </th>

  </tr>



</table>
</div>
@endif
