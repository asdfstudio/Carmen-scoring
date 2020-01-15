@if(!$division->sheet->criteria->isEmpty())
{!! Form::open(array('route' => array('judge.competition.division.round.choir.save_scores',$division->competition,$division,$round,$choir), 'method' => 'post')) !!}
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>
    <th data-judge-id="{{ $judge->id }}">My Scores</th>
  </tr>

  @foreach($judge->captions as $caption)
    @foreach($caption->criteria as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>

      <td data-criterion-id="{{ $criterion->id }}">
        @php $rawScore = $rawScores->where('criterion_id', $criterion->id)->pluck('score');@endphp
        @php $score = $rawScore->first(); @endphp
        {{ Form::number("scores[$criterion->id]", $score,['min' => 0, 'max' => 10, 'step' => '0.5']) }}
      </td>

    </tr>
    @endforeach
  @endforeach

  <tr>
  	<th>&nbsp;</th>
    <th>{{ Form::submit('Save Scores',['class' => 'btn btn-primary btn-lg']) }}</th>
  </tr>

</table>
</div>
{!! Form::close() !!}
@endif
