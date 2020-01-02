@if($rounds->isEmpty())
	<p>There are no rounds.</p>
@endif

@if(!$rounds->isEmpty())
<ul class="list-group">
  @foreach($rounds as $round)
	  <li class="round list-group-item">
			<span class="name">{{ link_to_route('organizer.competition.division.round.show', $round->name, [$division->competition,$division,$round]) }}</span>

			<span class="label status {{ $round->status_slug() }}">{{ $round->status() }}</span>

			<div>Order: {{ $round->sequence }}</div>

			<div>Number of participating choirs: {{ $round->max_choirs_text }}</div>

			@if(!$round->choirs->isEmpty())
				<h4>Choirs</h4>
				<ul>
					@foreach($round->choirs as $choir)
						<li>{{ $choir->full_name }}</li>
					@endforeach
				</ul>
			@endif

			@if(!$round->sources->isEmpty())
				<h4>Source(s) - {{ link_to_route('organizer.competition.division.round.show_sources', 'View combined scores', [$division->competition_id, $division, $round]) }}</h4>
				<ul>
					@foreach($round->sources as $source)
						<li>{{ link_to_route('organizer.competition.division.round.show', $source->full_name, [$division->competition_id, $division, $source->id]) }}</li>
					@endforeach
				</ul>
			@endif

			@if(!$round->targets)
				<h4>Target</h4>
				<ul>
					@foreach($round->targets as $target)
						<li>{{ $target->full_name }}</li>
					@endforeach
				</ul>
			@endif

			<ul class="actions-group">
				@can('update', $round)
					<li>
						{{ link_to_route('organizer.competition.division.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action']) }}
					</li>
				@endcan

				@can('setPerformanceOrder', $round)
					<li>
						{{ link_to_route('organizer.competition.division.round.choir.performance_order', 'Set Choir Performance Order', [$division->competition,$division,$round], ['class' => 'action']) }}
					</li>
				@endcan

				@can('activateScoring', $round)
					<li>
						{!! form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']) !!}
					</li>
				@endcan

				@can('deactivateScoring', $round)
					<li>
						{!! form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']) !!}
					</li>
				@endcan

				@can('completeScoring', $round)

					@php

					if ($round->isMissingScores()) {
	          $btnAttr = ['class' => 'action disabled', 'disabled' => 'disabled'];
	        } else {
	          $btnAttr = ['class' => 'action'];
	        }

					$completeScoringForm->modify('submit', 'submit', [
						'attr' => $btnAttr
					]);
					@endphp
					<li>
						{!! form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']) !!}
					</li>
				@endcan

				@can('reactivateScoring', $round)
					<li>
						{!! form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']) !!}
					</li>
				@endcan

			</ul>

		</li>
  @endforeach
</ul>
@endif
