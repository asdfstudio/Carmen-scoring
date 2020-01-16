<h1>Judges</h1>

@if($judges->isEmpty())
	<p>There are no judges.</p>
@endif

@if(!$judges->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Edit</th>
  </tr>

  @foreach($judges as $judge)
  <tr>
  	<td>{{ link_to_route('admin.judge.show', $judge->first_name, [$judge]) }}</td>
    <td>{{ link_to_route('admin.judge.show', $judge->last_name, [$judge]) }}</td>
    <td>{{ $judge->email }}</td>
    <td>{{ link_to_route('admin.judge.edit', 'Edit', [$judge]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
