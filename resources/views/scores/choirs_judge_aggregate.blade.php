

@if(!$choirs->isEmpty())
<table class="table last-col-right">
  <tr>
  	<th>Choir</th>
    <th>My Score</th>
    <th>My Rank</th>
    <th>Actions</th>
  </tr>

  @foreach($choirs as $choir)
  <tr>
  	<td>
      <span class="subheading">{{ $choir->school->name }}</span>
      {{ $choir->name }}
    </td>

    <td>
			<?php $aggregateScore = $rawScores->where('choir_id',$choir->id)->sum('score');?>
      {{ $aggregateScore }}
    </td>
    <td>
      -
    </td>
    <td>

      @if($round->is_scoring_active AND $judge->id == Auth::user()->person_id)

        <?php
        $anchor_text = 'Enter My Scores';

        if($aggregateScore > 0)
        {
          $anchor_text = 'Update My Scores';
        }

        ?>

        {{ link_to_route('judge.competition.division.round.choir.show', $anchor_text, [$round->division->competition,$round->division,$round,$choir],
        ['class' => 'action'])}}
      @endif


      @if($round->is_scoring_active == false AND $judge->id == Auth::user()->person_id)

        {{ link_to_route('judge.competition.division.round.choir.show', 'View My Scores', [$round->division->competition,$round->division,$round,$choir],
        ['class' => 'action'])}}

      @endif
    </td>
  </tr>
  @endforeach
</table>
@endif
