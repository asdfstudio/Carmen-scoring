@if($rounds->isEmpty())
	<p>There are no rounds.</p>
@endif

@if(!$rounds->isEmpty())
<ul class="list-group">
  @foreach($rounds as $round)
	  <li class="round list-group-item">
			<span class="name">{{ link_to_route('organizer.competition.division.round.show', $round->name, [$division->competition,$division,$round]) }}</span>
			<span class="label status {{ $round->status_slug() }}">{{ $round->status() }}</span>
			<ul class="actions-group">
				@can('update', $round)
					<li>
						{{ link_to_route('organizer.competition.division.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action']) }}
					</li>
				@endcan

				@can('activateScoring', $round)
					<li>
						{!! form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
					</li>
				@endcan

				@can('deactivateScoring', $round)
					<li>
						{!! form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
					</li>
				@endcan

				@can('completeScoring', $round)
					<li>
						{!! form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
					</li>
				@endcan

				@can('reactivateScoring', $round)
					<li>
						{!! form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
					</li>
				@endcan

			</ul>

		</li>
  @endforeach
</ul>
@endif
