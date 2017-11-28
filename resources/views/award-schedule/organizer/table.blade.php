@if($competition->awardSchedules->isEmpty())
	<p>There are no schedules.</p>
@endif

@if(!$competition->awardSchedules->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Actions</th>
  </tr>

  @foreach($competition->awardSchedules as $schedule)
  <tr>
  	<td>
			{{ link_to_route('organizer.competition.award-schedule.show', $schedule->name, [$competition, $schedule]) }}
		</td>
		<td>
			{{ link_to_route('organizer.competition.award-schedule.edit', 'Edit', [$competition,$schedule], ['class' => 'action']) }}

			{{ link_to_route('organizer.competition.award-schedule.builder', 'Build schedule', [$competition,$schedule], ['class' => 'action']) }}
		</td>
  </tr>
  @endforeach
</table>
@endif
