@if($users->isEmpty())
	<p>There are no users.</p>
@endif

@if(!$users->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
		<th>Name</th>
    <th>Email</th>
		<th>Admin?</th>
		<th>Judge?</th>
		<th>Organization</th>
		<th>Org. Role</th>
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
  	<td>{{ $user->email }}</td>
		<td>
			{{ $user->is_admin_text }}
		</td>
		<td>
			@if($user->person)
				{{ $user->person->is_judge_text }}
			@endif
		</td>

		<td>
			@if($user->organization_id)
				{{ $user->organization->name }}
			@endif
		</td>

		<td>
			{{ $user->organization_role }}
		</td>


		<td>
			@can('update' , $user)
				{{ link_to_route('admin.user.edit', 'Edit', [$user]) }}
			@endcan
		</td>


		<td>
			@can('destroy' , $user)
				{!! form($deleteUserForm,['url' => route('admin.user.destroy',[$user])]) !!}
			@endcan
		</td>
  </tr>
  @endforeach
</table>
</div>
@endif
