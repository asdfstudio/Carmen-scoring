@if($competitions->isEmpty())
	<p>There are no competitions.</p>
@endif

@if(!$competitions->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
		<th>Status</th>
  	<th>Competition Name</th>
    <th>City</th>
    <th>State</th>
    <!--<th>Divisions</th>-->
    <th>Edit</th>
		<th>Delete</th>
  </tr>

  @foreach($competitions as $competition)
  <tr>
		<td>{!! $competition->status_label() !!}</td>
  	<td>{{ link_to_route('organizer.competition.show', $competition->name, [$competition]) }}</td>
    <td>@if($competition->place) {{ $competition->place->city }} @endif</td>
    <td>@if($competition->place) {{ $competition->place->state }} @endif</td>
    <!--<td>{{ link_to_route('organizer.competition.division.index', $competition->divisions->count(), [$competition]) }}</td>-->
    <td>


    	@if(!$competition->is_archived)
				@can('update', $competition)
					{{ link_to_route('organizer.competition.edit', 'Edit', [$competition], ['class' => 'action']) }}
				@endcan
      @else
				<!--Archived, no editing allowed-->
			@endif

			@can('replicate', $competition)
				{{ link_to_route('organizer.competition.clone', 'Duplicate', [$competition], ['class' => 'action']) }}
			@endcan
    </td>
		<td>
			@can('destroy',$competition)
				{!! form($deleteCompetitionForm,['url' => route('organizer.competition.destroy',[$competition])]) !!}
			@endcan
		</td>
  </tr>
  @endforeach
</table>
</div>
@endif
