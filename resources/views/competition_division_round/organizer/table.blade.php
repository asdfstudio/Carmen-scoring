@if($division->rounds->isEmpty())
	<p>There are no rounds. {{ link_to_route('organizer.competition.division.round.create','Add one',[$division->competition,$division]) }}</p>
@endif

@if(!$division->rounds->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
    <th>Name</th>
		<th>Status</th>
  </tr>

  @foreach($division->rounds as $round)
  <tr>

    <td>{{ link_to_route('organizer.competition.division.round.show',$round->name,[$division->competition,$division,$round]) }}</td>
		<td>{{ $round->status() }}</td>
  </tr>
  @endforeach
</table>
@endif
