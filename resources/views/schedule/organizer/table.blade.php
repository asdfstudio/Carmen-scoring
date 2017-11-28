@if($competition->schedules->isEmpty())
	<p>There are no schedules.</p>
@endif

@if(!$competition->schedules->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Edit</th>
  </tr>

  @foreach($competition->schedules as $schedule)
  <tr>
  	<td>{{ link_to_route('organizer.competition.schedule.show', $schedule->name, [$competition, $schedule]) }}</td>
		<td>
			{{ link_to_route('organizer.competition.schedule.edit', 'Edit', [$competition,$schedule]) }}
		</td>
  </tr>
  @endforeach
</table>
@endif
