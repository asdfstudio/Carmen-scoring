@if($rounds->isEmpty())
	<p>There are no types.</p>
@endif

@if(!$rounds->isEmpty())
<ul class="list-group">
  @foreach($rounds as $round)
	  <li class="round list-group-item">
			<span class="name">{{ link_to_route('organizer.competition.round.show', $round->name, [$round->competition,$round]) }}</span>

			<span class="label status {{ $round->status_slug() }}">{{ $round->status() }}</span>

			<ul class="actions-group">
				@can('update', $round)
					<li>
						{{ link_to_route('organizer.competition.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action']) }}
					</li>
				@endcan

            {{--
				@can('setPerformanceOrder', $round)
					<li>
						{{ link_to_route('organizer.competition..round.choir.performance_order', 'Set Choir Performance Order', [$division->competition,$division,$round], ['class' => 'action']) }}
					</li>
				@endcan
            --}}

			</ul>
		</li>
  @endforeach
</ul>
@endif
