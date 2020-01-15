@if($competition->divisions->isEmpty())
	<p>There are no divisions.</p>
@endif

@if(!$competition->divisions->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Edit</th>
		<th>Set Up</th>
		<th>Status</th>
    <th>Weighting</th>
    <th>Scoring</th>
    <th>Sheet</th>
		<!--<th>Rounds</th>
    <th>Choirs</th>
    <th>Judges</th>
    <th>Penalties</th>
    <th>Awards</th>-->
  </tr>

  @foreach($competition->divisions as $division)
  <tr>
  	<td>{{ link_to_route('organizer.competition.division.show', $division->name, [$competition, $division]) }}</td>
		<td>
			@can('update', $division)
				{{ link_to_route('organizer.competition.division.edit', 'Edit', [$competition,$division]) }}
			@endcan
		</td>
		<td>
			@can('update', $division)
				{{ link_to_route('organizer.competition.division.board', 'Set Up', [$competition,$division]) }}
			@endcan
		</td>
		<td>{!! $division->status_label('small') !!}</td>
    <td>@if ($division->captionWeighting){{ $division->captionWeighting->name }} @endif</td>
    <td>@if ($division->scoringMethod){{ $division->scoringMethod->name }} @endif</td>
    <td>@if ($division->sheet){{ $division->sheet->name }} @endif</td>

		<!--<td>
			@php $anchor = $division->rounds->count() > 0 ? $division->rounds->count() : 'Set Up';@endphp
			{{ link_to_route('organizer.competition.division.round.index', $anchor, [$competition,$division]) }}

		</td>
    <td>
			@php $anchor = $division->choirs->count() > 0 ? $division->choirs->count() : 'Set Up';@endphp

			{{ link_to_route('organizer.competition.division.choir.index', $anchor, [$competition,$division]) }}

		</td>
    <td>
			@php $anchor = $division->judges->count() > 0 ? $division->judges->count() : 'Set Up';@endphp

			{{ link_to_route('organizer.competition.division.judge.index', $anchor, [$competition,$division]) }}

		</td>
    <td>{{ $division->penalties->count() }}</td>
    <td>{{ $division->awards->count() }}</td>-->
  </tr>
  @endforeach
</table>
</div>
@endif
