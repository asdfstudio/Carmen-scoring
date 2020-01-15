<h1>Organizations</h1>

@if($organizations->isEmpty())
	<p>There are no organizations.</p>
@endif

@if(!$organizations->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Organization Name</th>
    <th>City</th>
    <th>State</th>
    <th>Edit</th>
  </tr>

  @foreach($organizations as $organization)
  <tr>
  	<td>{{ link_to_route('admin.organization.show', $organization->name, [$organization]) }}</td>
    <td>@if($organization->place) {{ $organization->place->city }} @endif</td>
    <td>@if($organization->place) {{ $organization->place->state }} @endif</td>
    <td>{{ link_to_route('admin.organization.edit', 'Edit', [$organization]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
