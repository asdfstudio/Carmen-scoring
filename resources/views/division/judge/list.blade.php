@if(!$divisions->isEmpty())
	<ul class="list-group">
  @foreach($divisions as $division)
  <li class="list-group-item round">

		{{ link_to_route('judge.competition.division.details', $division->name, [$competition,$division], ['class' => 'name']) }}

		{!! $division->status_label('pull-right') !!}

		@if($division->rounds)
			<div class="clearfix mt">
			@foreach($division->rounds as $round)
				{{ link_to_route('judge.round.scores.summary', $round->name, [$competition, $division, $round], ['class' => 'action status-' . $round->status_slug, 'title' => $round->status]) }}
			@endforeach
		</div>
		@endif
	</li>
  @endforeach
  </ul>
@endif
