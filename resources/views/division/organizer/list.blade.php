@if(!$divisions->isEmpty())
	<ul class="list-group">
  @foreach($divisions as $division)
  <li class="list-group-item">{{ link_to_route('organizer.competition.division.settings', $division->name, [$division->competition,$division]) }}
		{!! $division->status_label('pull-right') !!}
	</li>
  @endforeach
  </ul>
@endif
