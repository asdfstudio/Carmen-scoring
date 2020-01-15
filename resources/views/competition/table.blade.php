<h1>Competitions</h1>

@if($competitions->isEmpty())
	<p>There are no competitions.</p>
@endif

@if(!$competitions->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Competition Name</th>
    <th>Organization</th>
    <th>City</th>
    <th>State</th>
    <th>Edit</th>
  </tr>

  @foreach($competitions as $competition)
  <tr>
  	<td>{{ link_to_route('admin.competition.show', $competition->name, [$competition]) }}</td>
    <td>@if($competition->organization) {{ link_to_route('admin.organization.show', $competition->organization->name, [$competition->organization]) }} @endif</td>
    <td>@if($competition->place) {{ $competition->place->city }} @endif</td>
    <td>@if($competition->place) {{ $competition->place->state }} @endif</td>
    <td>{{ link_to_route('admin.competition.edit', 'Edit', [$competition]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
