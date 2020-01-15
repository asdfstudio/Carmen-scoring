<h1>Users</h1>

@if($users->isEmpty())
	<p>There are no users.</p>
@endif

@if(!$users->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped">
  <tr>
  	<th>ID</th>
    <th>Email</th>
    <th>Is Admin?</th>
    <th>Organization</th>
    <th>Judge?</th>
    <th>Edit</th>
  </tr>
  
  @foreach($users as $user)
  <tr>
  	<td>{{ $user->id }}</td>
  	<td>{{ link_to_route('admin.user.show', $user->email, [$user]) }}</td>
    <td>{{ $user->isAdmin() }}</td>
    <td>@if($user->organization) {{ link_to_route('admin.organization.show', $user->organization->name, [$user->organization]) }} @endif</td>
    <td>@if($user->person) {{ link_to_route('admin.judge.show', $user->person->first_name, [$user->person]) }} @endif</td>
    <td>{{ link_to_route('admin.user.edit', 'Edit', [$user]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif