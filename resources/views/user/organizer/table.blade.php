@if($users->isEmpty())
	<p>There are no users.</p>
@endif

@if(!$users->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
		<th>Name</th>
		<th>Username</th>
    <th>Email</th>
		<th>Role Type</th>

	  <th>Edit</th>
		<th>Delete</th>
  </tr>

  @foreach($users as $user)
  <tr>
		<td>
			@if($user->person)
				{{ $user->person->full_name }}
			@endif
		</td>
		<td>{{ $user->username }}</td>
  	<td>{{ $user->email }}</td>
		<td>{{ $user->organization_role }}</td>


		<td>
			@can('update' , $user)
				{{ link_to_route('organizer.user.edit', 'Edit', [$user]) }}
			@endcan
		</td>


		<td>
			@can('destroy' , $user)
				{!! form($deleteUserForm,['url' => route('organizer.user.destroy',[$user])]) !!}
			@endcan
		</td>
  </tr>
  @endforeach
</table>
</div>
@endif
