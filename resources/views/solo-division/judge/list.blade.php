@if(!$soloDivisions->isEmpty())
	<ul class="list-group">
  @foreach($soloDivisions as $soloDivision)
  <li class="list-group-item">{{ link_to_route('judge.competition.solo-division.show', $soloDivision->name, [$competition,$soloDivision]) }}
		{!! $soloDivision->status_label('pull-right') !!}
	</li>
  @endforeach
  </ul>
@endif
