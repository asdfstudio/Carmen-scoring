@if(!$division->sheet->criteria->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>

    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">{{ link_to_route('judge.competition.division.round.choir.show',$choir->name, [$division->competition, $division, $round, $choir]) }}</th>
    @endforeach
  </tr>

  @foreach($judge->captions as $caption)
    @foreach($caption->criteria as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>

      @foreach($division->choirs as $choir)
      <td data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}">
        @if($round->is_completed OR $judge->id == Auth::user()->person_id)
          <?php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('choir_id',$choir->id)->pluck('score');?>
          <?php $score = $rawScore->first(); ?>
          {{ $score }}
        @else
          -
        @endif
      </td>
      @endforeach

    </tr>
    @endforeach
  @endforeach

  <tr>
  	<th>Total</th>

    @foreach($division->choirs as $choir)
    	<th>
      @if($round->is_completed OR $judge->id == Auth::user()->person_id)
    	<?php $aggregateScore = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('score');?>
      {{ $aggregateScore }}

      @else
      	-
      @endif
      </th>
    @endforeach

  </tr>
</table>
@endif
