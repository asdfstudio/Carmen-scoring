@if(!$division->sheet->criteria->isEmpty())
{!! Form::open(array('route' => array('competition.division.round.save_scores',$division->competition,$division,$round), 'method' => 'post')) !!}
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>

    @php $columnsCount = 1;@endphp
    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">{{ link_to_route('judge.competition.division.round.choir.show',$choir->name, [$division->competition, $division, $round, $choir]) }}</th>
    @php $columnsCount++;@endphp
    @endforeach
  </tr>


  @foreach($judge->captions as $caption)
    @foreach($caption->criteria as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>

      @foreach($division->choirs as $choir)
      <td data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}" class="row">
        @php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('choir_id',$choir->id)->pluck('score');@endphp
        @php $score = $rawScore->first(); @endphp
        {{ Form::number("scores[$choir->id][$criterion->id]", $score,['min' => 0, 'max' => 10, 'step' => '0.5', 'class' => 'col-xs-12']) }}
      </td>
      @endforeach

    </tr>
    @endforeach
  @endforeach

  <tr>
    <th colspan="{{ $columnsCount }}">{{ Form::submit('Save Scores',['class' => 'btn btn-primary btn-lg pull-right']) }}</th>
  </tr>

</table>
</div>
{!! Form::close() !!}
@endif
