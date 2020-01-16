@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
		<th>Name</th>
    <th>Description</th>
	  <th>Edit</th>
		<th>Delete</th>
  </tr>

  @foreach($awards as $award)
  <tr>
		<td>{{ $award->name }}</td>
  	<td>{{ $award->description }}</td>
		<td>
			@can('update' , $award)
				{{ link_to_route('organizer.award.edit', 'Edit', [$award]) }}
			@endcan
		</td>


		<td>
			@can('destroy' , $award)
				{!! form($deleteAwardForm,['url' => route('organizer.award.destroy',[$award])]) !!}
			@endcan
		</td>
  </tr>
  @endforeach
</table>
</div>
@endif
