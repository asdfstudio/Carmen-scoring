@if($rounds->isEmpty())
	<p>There are no rounds.</p>
@endif

@if(!$rounds->isEmpty())
<ul class="list-group">
  @foreach($rounds as $round)
	  <li class="round list-group-item">
			<span class="name">{{ link_to_route('judge.round.scores.summary', $round->name, [$division->competition,$division,$round]) }}</span>
			<span class="label status {{ $round->status_slug() }}">{{ $round->status() }}</span>

		</li>
  @endforeach
</ul>
@endif
