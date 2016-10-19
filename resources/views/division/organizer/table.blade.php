<h1>Divisions</h1>

@if($divisions->isEmpty())
	<p>There are no divisions.</p>
@endif

@if(!$divisions->isEmpty())
<table class="table table-striped">
  <tr>
  	<th>Name</th>
    <th>Edit</th>
  </tr>

  @foreach($divisions as $division)
  <tr>
  	<td>{{ link_to_route('organizer.competition.division.show', $division->name, [$division->competition,$division]) }}</td>
    <td>{{ link_to_route('organizer.competition.division.edit', 'Edit', [$division->competition,$division]) }}</td>
  </tr>
  @endforeach
</table>
@endif
