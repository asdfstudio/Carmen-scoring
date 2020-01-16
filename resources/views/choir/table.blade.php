<h1>Choirs</h1>

@if($choirs->isEmpty())
	<p>There are no choirs.</p>
@endif

@if(!$choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir Name</th>
    <th>School</th>
    <th>City</th>
    <th>State</th>
    <th>Edit</th>
  </tr>

  @foreach($choirs as $choir)
  <tr>
  	<td>{{ link_to_route('admin.choir.show', $choir->name, [$choir]) }}</td>
    <td>@if($choir->school) {{ link_to_route('admin.school.show', $choir->school->name, [$choir->school]) }} @endif</td>
    <td>@if($choir->school AND $choir->school->place) {{ $choir->school->place->city }} @endif</td>
    <td>@if($choir->school AND $choir->school->place) {{ $choir->school->place->state }} @endif</td>
    <td>{{ link_to_route('admin.choir.edit', 'Edit', [$choir]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
