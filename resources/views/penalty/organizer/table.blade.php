@if($penalties->isEmpty())
	<p>There are no penalties.</p>
@endif

@if(!$penalties->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
		<th>Name</th>
    <th>Description</th>
		<th>Amount</th>
		<th>
			Apply Per Judge?
		</th>

	  <th>Edit</th>
		<th>Delete</th>
  </tr>

  @foreach($penalties as $penalty)
  <tr>
		<td>{{ $penalty->name }}</td>
  	<td>{{ $penalty->description }}</td>
		<td>{{ $penalty->amount }}</td>
		<td>
			{{ $penalty->apply_per_judge() }}
		</td>


		<td>
			@can('update' , $penalty)
				{{ link_to_route('organizer.penalty.edit', 'Edit', [$penalty]) }}
			@endcan
		</td>


		<td>
			@can('destroy' , $penalty)
				{!! form($deletePenaltyForm,['url' => route('organizer.penalty.destroy',[$penalty])]) !!}
			@endcan
		</td>
  </tr>
  @endforeach
</table>
</div>
@endif
