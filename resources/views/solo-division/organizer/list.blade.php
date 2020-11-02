@if(!$soloDivisions->isEmpty())
	<ul class="list-group">
  @foreach($soloDivisions as $soloDivision)
  <li class="list-group-item">{{ link_to_route('organizer.competition.solo-division.show', $soloDivision->name, [$soloDivision->competition,$soloDivision]) }}
		{!! $soloDivision->status_label('pull-right') !!}
            <ul class="actions-group pull-right mv">
                @php $scoringUrl = route('organizer.competition.solo-division.update-status', [$soloDivision->competition, $soloDivision]); @endphp

                @can('activateScoring', $soloDivision)
                    <li> {!! form($scoringForms['activate'], ['url' => $scoringUrl]) !!}</li>
                @endcan

                @can('completeScoring', $soloDivision)
                    <li> {!! form($scoringForms['complete'], ['url' => $scoringUrl]) !!}</li>
                @endcan

                @can('finalizeScoring', $soloDivision)
                    <li> {!! form($scoringForms['finalize'], ['url' => $scoringUrl]) !!}</li>
                @endcan
            </ul>
	</li>
  @endforeach
  </ul>
@endif
