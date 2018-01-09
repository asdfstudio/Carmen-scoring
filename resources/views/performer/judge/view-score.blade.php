<table class="table table-bordered scoreboard last-col-right">


  @foreach($captions as $caption)
    <tr class="caption-header caption-{{ $caption->slug() }}">
      <th>
        {{ $caption->name }}
      </th>
      <th class="score">
        Score
      </th>
    </tr>

    @foreach($soloDivision->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr>
    	<td>{{ $criterion->name }}</td>
      <td>
      	<?php
        $rawScore = $rawScores->where('criterion_id', $criterion->id)->pluck('score');
        $score = $rawScore->first();
        ?>
        <span class="score raw">{{ $score }}</span>
      </td>
    </tr>
    @endforeach

    <tr class="caption-raw-score caption-{{ $caption->slug() }}">
      <th>
        Total {{ $caption->name }} Score
      </th>
      <th>
        <?php $rawTotal = $rawScores->where('criterion_caption_id', $caption->id)->sum('score');?>
        {{ $rawTotal }}
      </th>
    </tr>
  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    <th>
      <?php $rawTotal = $rawScores->sum('score');?>
      {{ $rawTotal }}
    </th>

  </tr>

</table>
