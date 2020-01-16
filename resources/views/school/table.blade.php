<h1>Schools</h1>

@if($schools->isEmpty())
	<p>There are no schools.</p>
@endif

@if(!$schools->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>School Name</th>
    <th>City</th>
    <th>State</th>
    <th>Edit</th>
  </tr>

  @foreach($schools as $school)
  <tr>
  	<td>{{ link_to_route('admin.school.show', $school->name, [$school]) }}</td>
    <td>@if($school->place) {{ $school->place->city }} @endif</td>
    <td>@if($school->place) {{ $school->place->state }} @endif</td>
    <td>{{ link_to_route('admin.school.edit', 'Edit', [$school]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
