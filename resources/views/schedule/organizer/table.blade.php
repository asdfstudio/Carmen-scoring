@if($competition->schedules->isEmpty())
	<p>There are no schedules.</p>
@endif

@if(!$competition->schedules->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Actions</th>
  </tr>

  @foreach($competition->schedules as $schedule)
  <tr>
  	<td>{{ $schedule->name }} </td>
		<td>
			{{ link_to_route('organizer.competition.schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action']) }}

			{{ link_to_route('organizer.competition.schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action']) }}

			{{ link_to_route('organizer.competition.schedule.builder', 'Schedule Builder', [$competition,$schedule], ['class' => 'action']) }}
		</td>
  </tr>
  @endforeach
</table>
@endif
