@if(!$divisions->isEmpty())
	<ul class="list-group">
  @foreach($divisions as $division)
  <li class="list-group-item">
		{{ link_to_route('judge.competition.division.show', $division->name, [$division->competition,$division]) }}
		{{ $division->status() }}

		@if($division->rounds)
			<div class="pull-right">
			@foreach($division->rounds as $round)
				{{ link_to_route('judge.round.scores.summary', $round->name, [$division->competition, $division, $round], ['class' => 'action']) }}
			@endforeach
		</div>
		@endif
	</li>
  @endforeach
  </ul>
@endif
