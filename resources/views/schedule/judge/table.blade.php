@if($competition->schedules->isEmpty())
	<p>There are no schedules.</p>
@endif

@if(!$competition->schedules->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
  </tr>

  @foreach($competition->schedules as $schedule)
  <tr>
  	<td>{{ link_to_route('judge.competition.schedule.show', $schedule->name, [$competition, $schedule]) }}</td>
  </tr>
  @endforeach
</table>
@endif
