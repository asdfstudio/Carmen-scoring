<table class="table table-bordered scoreboard last-col-right">


  @foreach($captions as $caption)
    <tr class="caption-header {{ $caption->background_css }}">
      <th>
        {{ $caption->name }}
      </th>

      <th class="score">
        Total Score
      </th>

      @foreach ($soloDivision->judges as $judge)
        <th class="score">
          {{ $judge->full_name }}
        </th>
      @endforeach

    </tr>

    @foreach($soloDivision->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr>
    	<td>{{ $criterion->name }}</td>
      <td>
      	@php
        $score = $rawScores->where('criterion_id', $criterion->id)->sum('score');
        @endphp
        <span class="score raw">{{ $score }}</span>
      </td>

      @foreach ($soloDivision->judges as $judge)
        <td>
          @php
          $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->pluck('score');
          $score = $rawScore->first();
          @endphp
          <span class="score raw">{{ $score }}</span>
        </td>
      @endforeach
    </tr>
    @endforeach

    <tr class="caption-raw-score {{ $caption->lighter_background_css }}">
      <th>
        Total {{ $caption->name }} Score
      </th>
      <th>
        @php $rawTotal = $rawScores->where('criterion.caption_id', $caption->id)->sum('score');@endphp
        {{ $rawTotal }}
      </th>

      @foreach ($soloDivision->judges as $judge)
        <th class="score">
          @php
          $score = $rawScores->where('judge_id', $judge->id)->sum('score');
          @endphp
          {{ $score }}
        </th>
      @endforeach
    </tr>
  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    <th>
      @php $rawTotal = $rawScores->sum('score');@endphp
      {{ $rawTotal }}
    </th>

    @foreach ($soloDivision->judges as $judge)
      <th>
        @php
        $score = $rawScores->where('judge_id', $judge->id)->sum('score');
        @endphp
        {{ $score }}
      </th>
    @endforeach

  </tr>

</table>
