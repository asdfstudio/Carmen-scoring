@if($competition->divisions->isEmpty())
	<p>There are no divisions.</p>
@endif

@if(!$competition->divisions->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Edit</th>
    <th>Caption Weighting</th>
    <th>Scoring Method</th>
    <th>Scoring Sheet</th>
		<th>Rounds</th>
    <th>Choirs</th>
    <th>Judges</th>
    <th>Penalties</th>
    <th>Awards</th>
  </tr>

  @foreach($competition->divisions as $division)
  <tr>
  	<td>{{ link_to_route('organizer.competition.division.show', $division->name, [$competition, $division]) }}</td>
		<td>{{ link_to_route('organizer.competition.division.edit', 'Edit', [$competition,$division]) }}</td>
    <td>@if ($division->captionWeighting){{ $division->captionWeighting->name }} @endif</td>
    <td>@if ($division->scoringMethod){{ $division->scoringMethod->name }} @endif</td>
    <td>@if ($division->sheet){{ $division->sheet->name }} @endif</td>

		<td>
			@if($division->rounds->count() > 0)
				{{ link_to_route('organizer.competition.division.round.index', $division->rounds->count(), [$competition,$division]) }}
			@else
				{{ link_to_route('organizer.competition.division.round.setup', 'Set Up', [$competition,$division]) }}
			@endif

		</td>
    <td>
			@if($division->choirs->count() > 0)
				{{ link_to_route('organizer.competition.division.choir.index', $division->choirs->count(), [$competition,$division]) }}
			@else
				{{ link_to_route('organizer.competition.division.choir.setup', 'Set Up', [$competition,$division]) }}
			@endif
		</td>
    <td>
			@if($division->judges->count() > 0)
				{{ link_to_route('organizer.competition.division.judge.index', $division->judges->count(), [$competition,$division]) }}
			@else
				{{ link_to_route('organizer.competition.division.judge.setup', 'Set Up', [$competition,$division]) }}
			@endif
		</td>
    <td>{{-- $division->penalties->count() --}}</td>
    <td>{{-- $division->awards->count() --}}</td>
  </tr>
  @endforeach
</table>
@endif
